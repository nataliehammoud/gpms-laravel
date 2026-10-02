// services/pcaService.js

// ========== PCA باستخدام الـ Jacobi Eigenvalue (خوارزمية بسيطة) ==========
function performPCA(vectors, dimensions) {
    // 1. حساب المتوسط
    const n = vectors.length;
    const dim = vectors[0].length;
    const mean = Array(dim).fill(0);
    for (let i = 0; i < n; i++) {
        for (let j = 0; j < dim; j++) {
            mean[j] += vectors[i][j] / n;
        }
    }

    // 2. طرح المتوسط (Centering)
    const centered = vectors.map(v => v.map((val, j) => val - mean[j]));

    // 3. حساب مصفوفة التغاير (Covariance)
    const cov = Array(dim).fill(0).map(() => Array(dim).fill(0));
    for (let i = 0; i < n; i++) {
        for (let j = 0; j < dim; j++) {
            for (let k = 0; k < dim; k++) {
                cov[j][k] += (centered[i][j] * centered[i][k]) / (n - 1);
            }
        }
    }

    // 4. حساب القيم الذاتية والمتجهات الذاتية (طريقة مبسطة)
    // (هنا نستخدم طريقة تقريبية، لكن للدقة يُفضل استخدام مكتبة مثل ml-pca)
    // للتطبيق العملي، نستخدم مكتبة جاهزة (انظر الملاحظة)
    // ...

    // 🔥 هنا نستخدم مكتبة بسيطة (سنحتاج تثبيت `ml-pca`)
    const PCA = require('ml-pca');
    const pca = new PCA(vectors);
    const result = pca.predict(vectors, { nComponents: dimensions });
    return result;
}

function performKMeans(data, k) {
    // باستخدام مكتبة `ml-kmeans`
    const KMeans = require('ml-kmeans');
    const result = KMeans.kmeans(data, k);
    return result.clusters;
}

module.exports = { performPCA, performKMeans };