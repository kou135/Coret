window.renderItemScoreChart = function(itemBeforeAfterData, canvasId = 'itemScoreChart') {
    const container = document.getElementById(canvasId);
    if (!container || !itemBeforeAfterData || itemBeforeAfterData.length === 0) return;

    container.innerHTML = '';

    const sortedData = [...itemBeforeAfterData].sort((a, b) => Number(a.question_id) - Number(b.question_id));

    const top3 = [...itemBeforeAfterData] 
        .sort((a, b) => a.after - b.after)  
        .slice(0, 3)  
        .map(item => item.question_id);  

    const tableContainer = document.createElement('div');
    tableContainer.className = 'rounded-lg overflow-hidden';
    container.appendChild(tableContainer);

    const headerRow = document.createElement('div');
    headerRow.className = 'py-3 px-6 font-medium text-gray-700 border-b border-placeholder';
    headerRow.innerHTML = `
    <div class="flex w-full">
        <div class="w-3/4 text-placeholder">アンケート項目</div>
        <div class="w-1/4 flex gap-4 text-placeholder">
            <div class="w-1/2 text-center text-sm">スコア</div>
            <div class="w-1/2 text-center text-sm">前回比</div>
        </div>
    </div>
    `
    tableContainer.appendChild(headerRow);

    sortedData.forEach((item, index) => {
        const isTop3 = top3.includes(Number(item.question_id));


        const row = document.createElement('div');
        row.className = `py-2 px-6 mt-1 items-center rounded-lg ${
            isTop3 ? 'bg-red-100' : 'bg-header'
        }`;

        const change = item.before !== null ? (item.after - item.before) : null;
        const changeClass = change > 0 ? 'text-bar' : change < 0 ? 'text-arrowRed' : 'text-gray-400' ;
        const changeIcon = change > 0 ? '↑' : change < 0 ? '↓' : '–' ;
        const changeText = change !== null ? `${Math.abs(change)}` : '–';
        
        const scoreColor = getScoreColor(item.after || 0);

        const formattedScore = item.after !== null ? Math.round(item.after) : '–';

                const questionNumber = item.question_number || '不明';
                const questionText = item.question_text || '質問内容がありません';
        
        
        row.innerHTML = `
        <div class="flex w-full items-center">
            <button class="w-3/4 text-gray font-medium question-item" data-question-id="${item.question_id}">
                Q${questionNumber}: ${questionText}<!-- 質問番号とテキストを表示 -->
            </button>
                <div class="w-1/4 flex gap-4 items-center ml-2">
                    <div class="w-1/2 text-center">
                    ${ 
                        item.after !== null 
                        ? `<span class="text-xl font-medium text-gray">${formattedScore}</span>`
                        : `<span class="text-gray">–</span>`
                    }
                    </div>
                    <div class="w-1/2 text-center ml-3">
                        <div class="flex justify-center items-center gap-x-1 ${changeClass} font-medium">
                            <div>${changeIcon}</div>
                            <div>${change !== null ? changeText : ''}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="message"></div>
        `;
        
        tableContainer.appendChild(row);
    });
    
    function getScoreColor(score) {
        if (score >= 70) {
            return 'text-teal-500';
        } else if (score >= 40) {
            return 'text-amber-500';
        } else {
            return 'text-red-500';
        }
    }

    document.querySelectorAll(".question-item").forEach((item) => {
        item.addEventListener("click", function () {
            const questionId = this.dataset.questionId;
            window.location.href = `/home/${questionId}`;
        });
    });
    
    // ページが完全に読み込まれた後に処理を実行
    window.onload = function () {
        // URLから`questionId`を取得
        const currentPath = window.location.pathname;
        const questionId = currentPath.split("/").pop(); // URLの末尾からquestionIdを抽出
    
        if (questionId) {
            // 保存されたquestion_idに対応する要素を探す
            const selectedItem = document.querySelector(`[data-question-id="${questionId}"]`);
    
            if (selectedItem) {
                // 背景色とボーダーを追加
                selectedItem.classList.add("bg-[#2BC7BC]", "border-l-4", "border-bar", "rounded-lg", "text-white");
    
                // 右向き矢印を追加
                const arrow = document.createElement("span");
                arrow.className = "arrow text-white text-2xl mr-2"; // 右向き矢印
                arrow.innerHTML = "▶︎";  // 右向き矢印
                selectedItem.prepend(arrow);  // 項目の左隣に追加
            }
        }
    };
    

};
