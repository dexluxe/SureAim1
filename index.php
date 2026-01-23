<?php
session_start();

// Lista de acesso (Whitelist)
$whitelist = ["ADMIN", "SS_OPERADOR", "GESTOR", "MATHEUS", "DG", "ICE"];

// Lógica de Login
$error = "";
if (isset($_POST['login'])) {
    $user = strtoupper($_POST['username']);
    if (in_array($user, $whitelist)) {
        $_SESSION['agent'] = $user;
        header("Location: index.php");
        exit();
    } else {
        $error = "Acesso Negado: Identificação não encontrada no sistema.";
    }
}

// Lógica de Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

$isLoggedIn = isset($_SESSION['agent']);
$agentName = $isLoggedIn ? $_SESSION['agent'] : "";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CENTRAL DE ANÁLISE | S.S. SYSTEM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;700&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #00f2ff;
            --secondary: #7000ff;
            --bg: #050505;
            --card-bg: rgba(15, 15, 25, 0.7);
            --border: rgba(0, 242, 255, 0.2);
            --text: #e0e0e0;
            --accent: #ff0055;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Rajdhani', sans-serif;
            background: var(--bg);
            color: var(--text);
            height: 100vh;
            overflow: hidden;
            background-image: 
                radial-gradient(circle at 50% 50%, rgba(112, 0, 255, 0.1) 0%, transparent 50%),
                linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%),
                linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06));
            background-size: 100% 100%, 100% 2px, 3px 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Scanline Effect */
        body::before {
            content: " ";
            display: block;
            position: absolute;
            top: 0; left: 0; bottom: 0; right: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.1) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.03), rgba(0, 255, 0, 0.01), rgba(0, 0, 255, 0.03));
            z-index: 10;
            background-size: 100% 3px, 3px 100%;
            pointer-events: none;
        }

        /* Login Container */
        .login-container {
            background: var(--card-bg);
            padding: 40px;
            border-radius: 5px;
            border: 1px solid var(--border);
            backdrop-filter: blur(10px);
            width: 400px;
            text-align: center;
            box-shadow: 0 0 30px rgba(0, 242, 255, 0.1);
            position: relative;
            z-index: 20;
            animation: fadeIn 0.8s ease-out;
        }

        .login-container h1 {
            font-size: 2rem;
            letter-spacing: 5px;
            margin-bottom: 30px;
            color: var(--primary);
            text-shadow: 0 0 10px var(--primary);
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-size: 0.8rem;
            text-transform: uppercase;
            margin-bottom: 5px;
            color: var(--primary);
            opacity: 0.8;
        }

        .input-group input {
            width: 100%;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid var(--border);
            padding: 12px;
            color: white;
            font-family: 'JetBrains Mono', monospace;
            outline: none;
            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 10px rgba(0, 242, 255, 0.3);
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
            font-family: 'Rajdhani', sans-serif;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: var(--primary);
            color: black;
            box-shadow: 0 0 20px var(--primary);
        }

        /* Dashboard Layout */
        .dashboard {
            display: grid;
            grid-template-columns: 300px 1fr 300px;
            grid-template-rows: 80px 1fr;
            gap: 20px;
            width: 95vw;
            height: 90vh;
            z-index: 20;
            animation: slideUp 0.6s ease-out;
        }

        header {
            grid-column: 1 / -1;
            background: var(--card-bg);
            border: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 3px;
        }

        .logo span { color: var(--primary); }

        .header-info {
            display: flex;
            gap: 40px;
            align-items: center;
        }

        .info-item {
            text-align: right;
        }

        .info-label { font-size: 0.7rem; opacity: 0.6; text-transform: uppercase; }
        .info-value { font-family: 'JetBrains Mono', monospace; color: var(--primary); }

        /* Sidebar & Main */
        .sidebar, .main-content, .right-panel {
            background: var(--card-bg);
            border: 1px solid var(--border);
            padding: 20px;
            overflow-y: auto;
        }

        h3 {
            font-size: 1rem;
            text-transform: uppercase;
            margin-bottom: 20px;
            border-left: 3px solid var(--primary);
            padding-left: 10px;
            letter-spacing: 2px;
        }

        /* Stats Cards */
        .stat-card {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 20px;
            margin-bottom: 15px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: "";
            position: absolute;
            top: 0; right: 0;
            width: 30px; height: 30px;
            background: linear-gradient(45deg, transparent 50%, var(--border) 50%);
        }

        .stat-title { font-size: 0.8rem; opacity: 0.7; margin-bottom: 10px; }
        .stat-flex { display: flex; justify-content: space-between; align-items: center; }
        .stat-value { font-size: 2.5rem; font-weight: bold; font-family: 'JetBrains Mono', monospace; }
        
        .btn-add {
            background: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
            width: 35px; height: 35px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-add:hover { background: var(--primary); color: black; }

        /* Point Table */
        .point-controls {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }

        .btn-point {
            padding: 10px;
            border: 1px solid;
            background: transparent;
            cursor: pointer;
            font-family: 'Rajdhani', sans-serif;
            font-weight: bold;
            text-transform: uppercase;
        }

        .btn-in { border-color: #00ff88; color: #00ff88; }
        .btn-in:hover { background: #00ff88; color: black; }
        .btn-out { border-color: #ff4444; color: #ff4444; }
        .btn-out:hover { background: #ff4444; color: black; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
        }

        th { text-align: left; padding: 10px; border-bottom: 1px solid var(--border); opacity: 0.5; }
        td { padding: 10px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); }

        .logout-link {
            color: var(--accent);
            text-decoration: none;
            font-size: 0.8rem;
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>

<?php if (!$isLoggedIn): ?>
    <div class="login-container">
        <i class="fas fa-terminal fa-3x" style="color: var(--primary); margin-bottom: 20px;"></i>
        <h1>S.S. SYSTEM</h1>
        <form method="POST">
            <div class="input-group">
                <label>Identificação do Agente</label>
                <input type="text" name="username" required autofocus autocomplete="off">
            </div>
            <button type="submit" name="login" class="btn-login">Inicializar Sistema</button>
        </form>
        <?php if ($error): ?>
            <p style="color: var(--accent); margin-top: 15px; font-size: 0.9rem;"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="dashboard">
        <header>
            <div class="logo">CENTRAL<span>ANALISTA</span></div>
            <div class="header-info">
                <div class="info-item">
                    <div class="info-label">Agente Ativo</div>
                    <div class="info-value"><?php echo $agentName; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Data do Sistema</div>
                    <div class="info-value" id="liveDate">--/--/----</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Relógio Atômico</div>
                    <div class="info-value" id="liveClock">00:00:00</div>
                </div>
                <a href="?logout=1" class="btn-add" style="display: flex; align-items: center; justify-content: center; border-color: var(--accent); color: var(--accent);">
                    <i class="fas fa-power-off"></i>
                </a>
            </div>
        </header>

        <div class="sidebar">
            <h3><i class="fas fa-fingerprint"></i> Terminal de Ponto</h3>
            <div class="point-controls">
                <button onclick="logPoint('ENTRADA')" class="btn-point btn-in">Entrada</button>
                <button onclick="logPoint('SAÍDA')" class="btn-point btn-out">Saída</button>
            </div>
            <table id="pointTable">
                <thead>
                    <tr>
                        <th>STATUS</th>
                        <th>HORA</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Logs via JS -->
                </tbody>
            </table>
        </div>

        <div class="main-content">
            <h3><i class="fas fa-chart-bar"></i> Monitoramento de Operações</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="stat-card">
                    <div class="stat-title">Análises Concluídas</div>
                    <div class="stat-flex">
                        <div class="stat-value" id="v1">0</div>
                        <button onclick="inc('v1')" class="btn-add"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="stat-card" style="border-left: 2px solid #e67e22;">
                    <div class="stat-title">WO's Registrados</div>
                    <div class="stat-flex">
                        <div class="stat-value" id="v2" style="color: #e67e22;">0</div>
                        <button onclick="inc('v2')" class="btn-add" style="border-color: #e67e22; color: #e67e22;"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="stat-card" style="border-left: 2px solid var(--accent);">
                    <div class="stat-title">Exposeds Detectados</div>
                    <div class="stat-flex">
                        <div class="stat-value" id="v3" style="color: var(--accent);">0</div>
                        <button onclick="inc('v3')" class="btn-add" style="border-color: var(--accent); color: var(--accent);"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Eficiência Operacional</div>
                    <div class="stat-flex">
                        <div class="stat-value" style="color: #00ff88;">98%</div>
                        <i class="fas fa-shield-check" style="color: #00ff88;"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card" style="margin-top: 20px;">
                <h3><i class="fas fa-broadcast-tower"></i> Comunicações Internas</h3>
                <p style="font-family: 'JetBrains Mono', monospace; font-size: 0.9rem; opacity: 0.8;">
                    > Sistema operacional estável.<br>
                    > Aguardando novas diretrizes da gerência.<br>
                    > Todos os protocolos de segurança ativos.
                </p>
            </div>
        </div>

        <div class="right-panel">
            <h3><i class="fas fa-tasks"></i> Enquete Operacional</h3>
            <div class="stat-card">
                <p style="margin-bottom: 15px;">Meta diária atingida?</p>
                <button class="btn-login" style="font-size: 0.8rem; margin-bottom: 10px;">Confirmar 100%</button>
                <button class="btn-login" style="font-size: 0.8rem; border-color: rgba(255,255,255,0.2); color: white;">Em Processamento</button>
            </div>
            
            <div style="margin-top: 30px; text-align: center; opacity: 0.3;">
                <i class="fas fa-microchip fa-4x"></i>
                <p style="font-size: 0.7rem; margin-top: 10px;">ENCRYPTED CONNECTION</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    // Relógio e Data
    function updateClock() {
        const now = new Date();
        const clockEl = document.getElementById('liveClock');
        const dateEl = document.getElementById('liveDate');
        
        if(clockEl) clockEl.innerText = now.toLocaleTimeString('pt-BR');
        if(dateEl) dateEl.innerText = now.toLocaleDateString('pt-BR');
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Funções de Interação
    function inc(id) {
        const el = document.getElementById(id);
        if(el) {
            let val = parseInt(el.innerText);
            el.innerText = val + 1;
            // Efeito de brilho ao aumentar
            el.style.textShadow = "0 0 20px var(--primary)";
            setTimeout(() => el.style.textShadow = "none", 300);
        }
    }

    function logPoint(type) {
        const now = new Date();
        const table = document.getElementById('pointTable').getElementsByTagName('tbody')[0];
        const row = table.insertRow(0);
        
        const color = type === 'ENTRADA' ? '#00ff88' : '#ff4444';
        const time = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0');
        
        row.innerHTML = `
            <td style="color: ${color}; font-weight: bold;">${type}</td>
            <td>${time}</td>
        `;
        
        row.style.animation = "fadeIn 0.5s ease-out";
    }
</script>

</body>
</html>
