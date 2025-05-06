<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>企業情報登録 - Coret</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white min-h-screen pt-3">
    <div class="w-5/6 max-w-full mx-auto">
        <div class="bg-box rounded-lg shadow-md w-full p-10 mt-10 mb-10">
            <x-regist-header>企業情報登録</x-regist-header>
            <x-progress-bar :steps="['企業情報', '組織構造', '入力内容確認', '登録完了']" :current="2" />

            <h3 class="font-noto text-center text-title text-lg mb-6 mt-10">企業の組織構造を教えてください。</h3>

            <form action="{{ route('company.register.step2.store') }}" method="POST" id="organizationForm"
                class="mt-12">
                @csrf
                <div class="relative mb-12">
                    <div id="cards_container"
                        class="flex justify-start space-x-6 overflow-x-auto scrollbar-thin scrollbar-thumb-gray-200 scrollbar-track-transparent snap-x snap-mandatory">

                        <div
                            class="bg-box border border-placeholder rounded-lg p-6 shadow-md min-w-[330px] max-w-[330px] flex-shrink-0 snap-start relative">
                            <div class="absolute top-4 left-4 text-title text-base font-semibold font-noto px-1">例：❓
                            </div>

                            <div class="mt-6">
                                <div class="mb-4">
                                    <div class="text-title text-sm mb-1">階層名</div>
                                    <input type="text" value="課"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-md bg-white text-title"
                                        readonly>
                                </div>

                                <div class="mb-4">
                                    <div class="text-title text-sm mb-1">親階層</div>
                                    <select class="w-full px-3 py-2 border border-gray-200 rounded-md text-title">
                                        <option>部署</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <div class="text-gray-600 text-sm mb-1">組織名</div>
                                    <input type="text" value="第一課"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-md bg-white text-title mb-2"
                                        readonly>
                                    <div class="text-title text-sm mb-1">親組織</div>
                                    <select
                                        class="w-full px-3 py-2 border border-gray-200 rounded-md bg-white text-title appearance-none">
                                        <option>営業部</option>
                                    </select>
                                </div>

                                <button type="button" disabled
                                    class="bg-bar text-white px-4 py-2 rounded-md text-sm flex items-center cursor-default hover:bg-hoverBar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    追加
                                </button>

                                <ul class="mt-3 space-x-3 text-title text-sm flex">
                                    <li>・第一課</li>
                                    <li>-- 営業部 --</li>
                                </ul>
                            </div>
                        </div>

                    </div>

                    <div class="absolute right-px top-1/2 transform -translate-y-1/2 z-10">
                        <button type="button" id="add_card"
                            class="w-14 h-14 bg-bar text-white rounded-full hover:bg-hoverBar flex items-center justify-center shadow-md focus:outline-none">
                            <span class="text-2xl">＋</span>
                        </button>
                    </div>
                </div>

                <input type="hidden" name="organizations" id="organizations_data" value='@json($organizations)'>

                <x-next-button>次へ</x-next-button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cardsContainer = document.getElementById('cards_container');
            const addCardButton = document.getElementById('add_card');
            const hiddenInput = document.getElementById('organizations_data');
            let cardData = [];
            let orgNameOptions = [];
            let globalOrgIndex = 0;
            let initialData = JSON.parse(hiddenInput.value || '[]');

            function refreshParentSelects() {
                document.querySelectorAll('.parent-select').forEach((select, i) => {
                    const currentValue = select.value;
                    select.innerHTML = '<option value="">-- 親階層なし --</option>' +
                        cardData.map((card, j) => {
                            if (i !== j) {
                                const name = card.querySelector('.hierarchy-name')?.value ||
                                    `階層 ${j + 1}`;
                                return `<option value="${j}" ${currentValue == j ? 'selected' : ''}>${name}</option>`;
                            }
                            return '';
                        }).join('');
                });
            }

            function createCard(index, data = {}) {
                const card = document.createElement('div');
                card.className =
                    'bg-white border border-gray-200 rounded-lg p-6 shadow-sm min-w-[400px] max-w-[400px] flex-shrink-0 snap-start';
                card.dataset.index = index;

                const hierarchyValue = data.hierarchy || '';
                const parentIndex = data.parentIndex ?? '';
                const names = data.names || [];
                const parentOrgIndexes = data.parentOrgIndexes || [];

                card.orgNames = [...names];
                card.parentOrgIndexes = [...parentOrgIndexes];

                card.innerHTML = `
                <div class="w-full">
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-title mb-1">階層名</label>
                        <input type="text" class="hierarchy-name w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-1 focus:ring-teal-500" value="${hierarchyValue}" placeholder="例：部署">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-title mb-1">親階層</label>
                        <div class="relative">
                            <select class="parent-select w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-1 focus:ring-teal-500 pr-8 text-title">
                                <option value="">-- 親階層なし --</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-title mb-1">組織名</label>
                        <input type="text" class="org-name w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-1 focus:ring-teal-500 mb-2" placeholder="例：営業部">
                        <label class="block text-sm font-medium text-title mb-1">親組織</label>
                        <div class="relative mb-2">
                            <select class="parent-org-select w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-1 focus:ring-teal-500 pr-8 text-title">
                                <option value="">-- 親組織なし --</option>
                                ${orgNameOptions.map(opt => `<option value="${opt.index}">${opt.name}</option>`).join('')}
                            </select>
                        </div>
                        <button type="button" class="add-org bg-bar text-white px-4 py-2 rounded-md text-sm hover:bg-hoverBar flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            追加
                        </button>
                    </div>

                    <ul class="org-list space-y-1 text-gray-700"></ul>
                </div>
            `;

                const addOrgButton = card.querySelector('.add-org');
                const orgNameInput = card.querySelector('.org-name');
                const parentOrgSelect = card.querySelector('.parent-org-select');
                const orgList = card.querySelector('.org-list');

                card.orgNames.forEach((name, i) => {
                    const parent = card.parentOrgIndexes[i];
                    orgNameOptions.push({
                        name,
                        index: globalOrgIndex++
                    });
                    addOrgToList(name, parent, orgList);
                });

                addOrgButton.addEventListener('click', () => {
                    const name = orgNameInput.value.trim();
                    const parentIndex = parentOrgSelect.value !== '' ? parseInt(parentOrgSelect.value) :
                        null;
                    if (!name) return;

                    card.orgNames.push(name);
                    card.parentOrgIndexes.push(parentIndex);
                    const newOrgIndex = globalOrgIndex++;
                    orgNameOptions.push({
                        name,
                        index: newOrgIndex
                    });

                    addOrgToList(name, parentIndex, orgList);
                    orgNameInput.value = '';

                    updateParentOrgSelects();
                });

                card.getData = function() {
                    const hierarchyName = card.querySelector('.hierarchy-name').value.trim();
                    const parentIndex = card.querySelector('.parent-select').value;
                    return hierarchyName ? {
                        hierarchy: hierarchyName,
                        names: card.orgNames,
                        parentIndex: parentIndex !== '' ? parseInt(parentIndex) : null,
                        parentOrgIndexes: card.parentOrgIndexes
                    } : null;
                };

                return card;
            }

            function addOrgToList(name, parentIndex, orgList) {
                const ul = document.createElement('ul');
                ul.className = 'mt-2 space-x-3 text-title text-sm flex';

                const childLi = document.createElement('li');
                childLi.textContent = `・${name}`;

                const parentOrg = parentIndex !== null ? orgNameOptions.find(opt => opt.index === parentIndex)
                    ?.name : null;
                const parentLi = document.createElement('li');
                parentLi.textContent = parentOrg ? `-- ${parentOrg} --` : '-- 親組織なし --';

                ul.appendChild(childLi);
                ul.appendChild(parentLi);
                orgList.appendChild(ul);
            }


            function updateParentOrgSelects() {
                document.querySelectorAll('.parent-org-select').forEach(select => {
                    const currentValue = select.value;
                    select.innerHTML = '<option value="">-- 親組織なし --</option>' +
                        orgNameOptions.map(opt =>
                            `<option value="${opt.index}" ${currentValue == opt.index ? 'selected' : ''}>${opt.name}</option>`
                            ).join('');
                });
            }

            addCardButton.addEventListener('click', () => {
                const newCard = createCard(cardData.length);
                cardsContainer.appendChild(newCard);
                cardData.push(newCard);
                refreshParentSelects();

                setTimeout(() => {
                    newCard.scrollIntoView({
                        behavior: 'smooth',
                        inline: 'end',
                        block: 'nearest'
                    });
                }, 100);
            });

            document.getElementById('organizationForm').addEventListener('submit', (e) => {
                const allData = cardData.map(card => card.getData()).filter(item => item && item.names
                    .length > 0);
                if (allData.length === 0) {
                    e.preventDefault();
                    alert("最低1つ以上の組織を登録してください");
                    return;
                }
                hiddenInput.value = JSON.stringify(allData);
            });

            if (initialData.length > 0) {
                initialData.forEach((item, i) => {
                    const card = createCard(cardData.length, item);
                    cardsContainer.appendChild(card);
                    cardData.push(card);
                });
                refreshParentSelects();
                updateParentOrgSelects();
            } else {

                addCardButton.click();
            }
        });
    </script>
</body>

</html>
