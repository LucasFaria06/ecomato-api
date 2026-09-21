let chartsInitialized = false;

// Realizar Login
function realizarLogin(e) {
    e.preventDefault();
    document.getElementById('view-login').classList.add('hidden');
    document.getElementById('view-app').classList.remove('hidden');

    // Inicializa os gráficos ao entrar na dashboard
    initCharts();
}

// Realizar Logout
function realizarLogout() {
    document.getElementById('view-app').classList.add('hidden');
    document.getElementById('view-login').classList.remove('hidden');
}

// Alternar Menu Mobile
function toggleMobileMenu() {
    const sidebar = document.getElementById('sidebarNav');
    if (sidebar.style.transform === 'translateX(0px)') {
        sidebar.style.transform = 'translateX(-100%)';
    } else {
        sidebar.style.transform = 'translateX(0px)';
    }
}

// Sistema SPA: Navegação entre Telas
function navegar(paginaId) {
    // Oculta todas as telas
    document.querySelectorAll('.view-page').forEach(page => {
        page.classList.add('hidden');
    });

    // Exibe a página selecionada
    const targetPage = document.getElementById('page-' + paginaId);
    if (targetPage) {
        targetPage.classList.remove('hidden');
    }

    // Atualizar estado ativo dos links na sidebar
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    const activeLink = document.getElementById('nav-' + paginaId);
    if (activeLink) {
        activeLink.classList.add('active');
    }

    // Atualiza Título e Subtítulo do Header
    const headerTitle = document.getElementById('header-title-text');
    const headerSubtitle = document.getElementById('header-subtitle-text');

    if (paginaId === 'dashboard') {
        headerTitle.innerText = 'Dashboard Ambiental';
        headerSubtitle.innerText = 'Visão geral dos indicadores de sustentabilidade';
    } else if (paginaId === 'residuos') {
        headerTitle.innerText = 'Gestão de Resíduos';
        headerSubtitle.innerText = 'Registro e rastreabilidade de resíduos industriais';
    } else if (paginaId === 'form-residuo') {
        headerTitle.innerText = 'Novo Registro de Resíduo';
        headerSubtitle.innerText = 'Cadastre a geração ou destinação de resíduos';
    }

    // Fechar menu mobile se estiver aberto
    if (window.innerWidth < 768) {
        document.getElementById('sidebarNav').style.transform = 'translateX(-100%)';
    }
}

// Salvar Novo Resíduo
function salvarResiduo(e) {
    e.preventDefault();

    const dataVal = document.getElementById('formResiduoData').value;
    const mtrVal = document.getElementById('formResiduoMtr').value;
    const tipoVal = document.getElementById('formResiduoTipo').value;
    const classeVal = document.getElementById('formResiduoClasse').value;
    const qtdVal = document.getElementById('formResiduoQtd').value;
    const unidadeVal = document.getElementById('formResiduoUnidade').value;
    const destinadoraVal = document.getElementById('formResiduoDestinadora').value;

    // Formatação simples da data (AAAA-MM-DD para DD/MM/AAAA)
    const dateParts = dataVal.split('-');
    const dataFormatada = dateParts.length === 3 ? `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}` : dataVal;

    // Mapeamento de Badges por classe
    let badgeClasseHtml = '';
    if (classeVal === '1') {
        badgeClasseHtml = '<span class="badge-class badge-class-1">Classe I</span>';
    } else if (classeVal === '2a') {
        badgeClasseHtml = '<span class="badge-class badge-class-2a">Classe II A</span>';
    } else {
        badgeClasseHtml = '<span class="badge-class badge-class-2b">Classe II B</span>';
    }

    // Adiciona nova linha na tabela
    const tbody = document.getElementById('tabelaResiduosCorpo');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td>${dataFormatada}</td>
        <td><strong>${mtrVal}</strong></td>
        <td>${tipoVal}</td>
        <td>${badgeClasseHtml}</td>
        <td>${qtdVal} ${unidadeVal}</td>
        <td>${destinadoraVal}</td>
        <td><span class="badge-status badge-status-done">✅ Concluído</span></td>
    `;

    tbody.insertBefore(newRow, tbody.firstChild);

    // Reseta o formulário e retorna à listagem
    e.target.reset();
    navegar('residuos');
}

// Inicialização dos Gráficos com Chart.js
function initCharts() {
    if (chartsInitialized) return;
    chartsInitialized = true;

    // Gráfico de Consumo Hídrico (Linha)
    const ctxConsumo = document.getElementById('chartConsumoCanvas').getContext('2d');
    new Chart(ctxConsumo, {
        type: 'line',
        data: {
            labels: ['Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set'],
            datasets: [{
                label: 'Consumo Hídrico (m³)',
                data: [510, 480, 495, 470, 460, 450],
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.35
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    grid: { color: '#F1F5F9' },
                    ticks: { font: { family: 'Inter' } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Inter' } }
                }
            }
        }
    });

    // Gráfico de Divisão de Resíduos (Rosca)
    const ctxResiduos = document.getElementById('chartResiduosCanvas').getContext('2d');
    new Chart(ctxResiduos, {
        type: 'doughnut',
        data: {
            labels: ['Classe II B (Inertes)', 'Classe II A (Orgânicos)', 'Classe I (Perigosos)'],
            datasets: [{
                data: [55, 30, 15],
                backgroundColor: ['#22C55E', '#F59E0B', '#DC2626'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { family: 'Inter', size: 12 }, padding: 15 }
                }
            },
            cutout: '70%'
        }
    });
}