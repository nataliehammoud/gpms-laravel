// services/smartRecommendations.js
const { pipeline } = require('@xenova/transformers');

let extractor = null;

async function getExtractor() {
    if (!extractor) {
        console.log('🔄 جاري تحميل نموذج الذكاء الاصطناعي للتوصيات...');
        // ✅ نموذج all-MiniLM-L6-v2 كان شغال معك سابقاً
        extractor = await pipeline('feature-extraction', 'Xenova/all-MiniLM-L6-v2', {
            use_auth_token: false
        });
        console.log('✅ تم تحميل نموذج التوصيات بنجاح');
    }
    return extractor;
}

async function getEmbedding(text) {
    const extractor = await getExtractor();
    const result = await extractor(text, { pooling: 'mean', normalize: true });
    return Array.from(result.data);
}

function cosineSimilarity(vecA, vecB) {
    let dotProduct = 0, normA = 0, normB = 0;
    for (let i = 0; i < vecA.length; i++) {
        dotProduct += vecA[i] * vecB[i];
        normA += vecA[i] * vecA[i];
        normB += vecB[i] * vecB[i];
    }
    return dotProduct / (Math.sqrt(normA) * Math.sqrt(normB));
}

async function recommendSupervisors(projectTitle, projectDescription, supervisors) {
    try {
        const projectText = `${projectTitle}. ${projectDescription}`;
        const projectEmbedding = await getEmbedding(projectText);

        const recommendations = [];
        for (const supervisor of supervisors) {
            const supervisorText = `${supervisor.specialization} ${supervisor.name}`;
            const supervisorEmbedding = await getEmbedding(supervisorText);
            const similarity = cosineSimilarity(projectEmbedding, supervisorEmbedding);
            recommendations.push({
                supervisor_id: supervisor.id,
                name: supervisor.name,
                specialization: supervisor.specialization,
                similarity_score: Math.round(similarity * 100),
            });
        }

        recommendations.sort((a, b) => b.similarity_score - a.similarity_score);
        return { success: true, recommendations };
    } catch (error) {
        console.error('❌ خطأ في التوصيات:', error);
        return { success: false, message: error.message };
    }
}

module.exports = {
    recommendSupervisors
};
