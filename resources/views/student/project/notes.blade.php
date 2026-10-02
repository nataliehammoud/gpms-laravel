<x-app-layout>
<style>
    :root {
        --bg-pearl: #f4f1ea;
        --navy-soft: #2c3e50;
        --gold-luxe: #c5a059;
        --glass: rgba(255, 255, 255, 0.4);
    }

    body {
        background: radial-gradient(circle at center, #fff9f0 0%, var(--bg-pearl) 100%) !important;
        overflow-x: hidden;
    }

    .canvas-area {
        position: relative;
        min-height: 80vh;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .floating-title {
        position: absolute;
        top: 20px;
        text-align: center;
        z-index: 10;
    }

    .floating-title h2 {
        font-weight: 900;
        color: var(--navy-soft);
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.05);
    }

    .note-node {
        position: absolute;
        background: var(--glass);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(197, 160, 89, 0.3);
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        padding: 20px;
        text-align: center;
        z-index: 5;
        opacity: 0;
        transform: scale(0.5);
        width: 160px;
        height: 160px;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .note-node:hover {
        background: var(--navy-soft);
        transform: scale(1.1) rotate(5deg);
        z-index: 100;
        border-color: var(--gold-luxe);
    }

    .note-node:hover .node-text, .note-node:hover i {
        color: var(--gold-luxe) !important;
    }

    .node-text {
        color: var(--navy-soft);
        font-weight: 600;
        font-size: 0.9rem;
        max-width: 120px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        transition: 0.3s;
    }

    .node-icon {
        font-size: 1.5rem;
        color: var(--gold-luxe);
        margin-bottom: 8px;
    }

    .connections {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        pointer-events: none;
    }

    .node-detail {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 90%;
        max-width: 500px;
        background: white;
        padding: 40px;
        border-radius: 30px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.2);
        z-index: 1000;
        border-top: 8px solid var(--gold-luxe);
    }

    .overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(44, 62, 80, 0.4);
        backdrop-filter: blur(5px);
        z-index: 999;
    }

    .back-dock {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 100;
    }

    .btn-ultra-modern {
        background: var(--navy-soft);
        color: white;
        padding: 15px 40px;
        border-radius: 100px;
        border: 2px solid var(--gold-luxe);
        font-weight: bold;
        transition: 0.4s;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        text-decoration: none;
        display: inline-block;
    }

    .btn-ultra-modern:hover {
        background: var(--gold-luxe);
        color: var(--navy-soft);
        transform: translateY(-3px);
    }

    .btn-sentiment {
        background: #6f42c1;
        padding: 8px 20px;
        font-size: 0.9rem;
        width: 100%;
        margin-bottom: 10px;
        border-color: #6f42c1;
    }
    .btn-sentiment:hover {
        background: #5a32a3;
        border-color: #5a32a3;
    }
</style>

<div class="canvas-area" id="canvas">
    <div class="floating-title">
        <i class="fa-solid fa-brain fa-3x mb-3" style="color: var(--gold-luxe)"></i>
        <h2>خارطة توجيهات المشروع</h2>
    </div>

    <!-- خطوط الربط الخلفية -->
    <svg class="connections" id="svg-canvas"></svg>

    @foreach($project->notes as $index => $note)
        <div class="note-node"
             style="width: {{ 150 + rand(0, 50) }}px; height: {{ 150 + rand(0, 50) }}px;"
             onclick="showNote('{{ addslashes($note->content) }}', '{{ $note->created_at->format('Y-m-d') }}')">
            <i class="fa-solid fa-pen-nib node-icon"></i>
            <div class="node-text">{{ $note->content }}</div>
            <small style="font-size: 0.7rem; color: #999;">{{ $note->created_at->format('M d') }}</small>
        </div>
    @endforeach

    @if($project->notes->isEmpty())
        <div class="text-center" style="z-index: 10">
            <i class="fa-solid fa-wind fa-4x mb-3 text-muted"></i>
            <h4 class="text-muted">المساحة هادئة.. لا ملاحظات بعد</h4>
        </div>
    @endif
</div>

<!-- تفاصيل الملاحظة (مودال) -->
<div class="overlay" id="overlay" onclick="closeNote()"></div>
<div class="node-detail" id="nodeDetail">
    <i class="fa-solid fa-quote-left fa-2x mb-3" style="color: var(--gold-luxe)"></i>
    <div id="fullNoteContent" class="mb-4" style="font-size: 1.2rem; line-height: 1.8; color: var(--navy-soft)"></div>

    {{-- ✅ زر تحليل المشاعر --}}
    <button onclick="analyzeNoteSentiment()" class="btn-ultra-modern btn-sentiment">
        <i class="fa-solid fa-face-smile"></i> تحليل مشاعر الملاحظة
    </button>

    <div id="sentimentResult" style="display: none; padding: 12px; border-radius: 12px; margin-bottom: 15px; font-size: 0.95rem;"></div>

    <div class="text-muted border-top pt-3 d-flex justify-content-between">
        <span id="noteDate"></span>
        <i class="fa-solid fa-brain text-gold-luxe"></i>
    </div>
</div>

<div class="back-dock">
    <a href="{{ route('student.project.index') }}" class="btn-ultra-modern">
        <i class="fa-solid fa-arrow-left-long me-2"></i> العودة إلى لوحة الطالب
    </a>
</div>

<script>
    // ========== دوال المودال ==========
    function showNote(content, date) {
        document.getElementById('fullNoteContent').innerText = content;
        document.getElementById('noteDate').innerHTML = `<i class="fa-regular fa-calendar-days me-2"></i> ${date}`;

        // إخفاء نتيجة التحليل السابقة
        document.getElementById('sentimentResult').style.display = 'none';
        document.getElementById('sentimentResult').innerHTML = '';

        document.getElementById('nodeDetail').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
    }

    function closeNote() {
        document.getElementById('nodeDetail').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }

    // ========== تحليل مشاعر الملاحظة ==========
    function analyzeNoteSentiment() {
        const noteContent = document.getElementById('fullNoteContent').innerText;
        const resultDiv = document.getElementById('sentimentResult');

        if (!noteContent.trim()) {
            alert('⚠️ لا يوجد نص لتحليله');
            return;
        }

        resultDiv.style.display = 'block';
        resultDiv.innerHTML = '<p style="color: #6c757d; margin: 0;">⏳ جاري تحليل المشاعر...</p>';
        resultDiv.style.background = '#f8f9fa';
        resultDiv.style.borderRadius = '8px';
        resultDiv.style.padding = '12px';

        fetch('/analyze/sentiment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ text: noteContent })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const emoji = data.emoji || (data.label === 'POSITIVE' ? '😊' : data.label === 'NEGATIVE' ? '😞' : '😐');
                const bgColor = data.label === 'POSITIVE' ? '#d4edda' : data.label === 'NEGATIVE' ? '#f8d7da' : '#fff3cd';
                const textColor = data.label === 'POSITIVE' ? '#155724' : data.label === 'NEGATIVE' ? '#721c24' : '#856404';
                const labelText = data.label === 'POSITIVE' ? 'إيجابية' : data.label === 'NEGATIVE' ? 'سلبية' : 'محايدة';

                resultDiv.innerHTML = `
                    <div style="background: ${bgColor}; padding: 12px; border-radius: 8px;">
                        <p style="margin: 0; font-weight: 600; color: ${textColor};">
                            ${emoji} المشاعر: ${labelText}
                            <span style="font-weight: normal; font-size: 0.85rem;">(الثقة: ${Math.round(data.score * 100)}%)</span>
                        </p>
                        <p style="margin: 5px 0 0; font-size: 0.85rem; color: ${textColor}; opacity: 0.8;">
                            ${data.description || (data.label === 'POSITIVE' ? 'نص إيجابي' : data.label === 'NEGATIVE' ? 'نص سلبي' : 'نص محايد')}
                        </p>
                    </div>
                `;
                resultDiv.style.background = 'transparent';
            } else {
                resultDiv.innerHTML = `<p style="color: #dc3545; margin: 0;">❌ فشل التحليل: ${data.message || 'خطأ غير معروف'}</p>`;
            }
        })
        .catch(error => {
            resultDiv.innerHTML = `<p style="color: #dc3545; margin: 0;">❌ خطأ: ${error.message}</p>`;
        });
    }

   // ========== توزيع الفقاعات بشكل شبكي منظم ==========
document.addEventListener('DOMContentLoaded', function() {
    const nodes = document.querySelectorAll('.note-node');
    const canvas = document.getElementById('canvas');

    if (!canvas || nodes.length === 0) return;

    const cols = 4; // عدد الأعمدة
    const spacingX = 220; // المسافة بين الأعمدة
    const spacingY = 200; // المسافة بين الصفوف
    const startX = (canvas.offsetWidth - (cols - 1) * spacingX) / 2;
    const startY = 100;

    nodes.forEach((node, index) => {
        const row = Math.floor(index / cols);
        const col = index % cols;

        const x = startX + col * spacingX - (node.offsetWidth / 2);
        const y = startY + row * spacingY - (node.offsetHeight / 2);

        node.style.left = x + 'px';
        node.style.top = y + 'px';

        setTimeout(() => {
            node.style.opacity = '1';
            node.style.transform = 'scale(1)';
        }, index * 150);
    });
});
</script>
</x-app-layout>
