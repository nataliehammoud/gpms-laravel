// services/pcaService.cjs

const KMeans = require('ml-kmeans');

/**
 * تنفيذ PCA يدوياً (بدون مكتبة خارجية)
 * باستخدام خوارزمية تقريبية تعتمد على تحليل القيم الذاتية بطريقة مبسطة
 */
function performPCA(vectors, dimensions = 2) {
    const n = vectors.length;
    const dim = vectors[0].length;
    
    // 1. حساب المتوسط لكل بُعد
    const mean = Array(dim).fill(0);
    for (let i = 0; i < n; i++) {
        for (let j = 0; j < dim; j++) {
            mean[j] += vectors[i][j] / n;
        }
    }
    
    // 2. طرح المتوسط (Centering)
    const centered = vectors.map(v => v.map((val, j) => val - mean[j]));
    
    // 3. حساب مصفوفة التغاير (Covariance Matrix)
    const cov = Array(dim).fill(0).map(() => Array(dim).fill(0));
    for (let i = 0; i < n; i++) {
        for (let j = 0; j < dim; j++) {
            for (let k = 0; k < dim; k++) {
                cov[j][k] += (centered[i][j] * centered[i][k]) / (n - 1);
            }
        }
    }
    
    // 4. حساب القيم الذاتية والمتجهات الذاتية بطريقة تقريبية (Power Iteration)
    // نستخدم طريقة بسيطة لتقليل الأبعاد: نأخذ أول dimension متجهات ذاتية تقريبية
    const eigenVectors = [];
    for (let d = 0; d < dimensions; d++) {
        // نبدأ بمتجه عشوائي
        let v = Array(dim).fill(0).map(() => Math.random());
        // نكرر 10 مرات لتقريب المتجه الذاتي
        for (let iter = 0; iter < 10; iter++) {
            let w = Array(dim).fill(0);
            for (let i = 0; i < dim; i++) {
                for (let j = 0; j < dim; j++) {
                    w[i] += cov[i][j] * v[j];
                }
            }
            // تطبيع
            let norm = Math.sqrt(w.reduce((sum, val) => sum + val * val, 0));
            if (norm === 0) break;
            v = w.map(val => val / norm);
        }
        eigenVectors.push(v);
    }
    
    // 5. إسقاط البيانات على المتجهات الذاتية
    const projected = vectors.map(v => {
        return eigenVectors.map(ev => {
            let sum = 0;
            for (let i = 0; i < dim; i++) {
                sum += v[i] * ev[i];
            }
            return sum;
        });
    });
    
    return projected;
}

/**
 * تنفيذ K-Means
 */
function performKMeans(data, k = 4) {
    const result = KMeans.kmeans(data, k);
    return result.clusters;
}

module.exports = { performPCA, performKMeans };