<?php
// 檢查瀏覽器有沒有送來名為 username 的 Cookie
$is_logged_in = isset($_COOKIE['username']);
$current_user = $is_logged_in ? $_COOKIE['username'] : "";
$login_time = isset($_COOKIE['login_time']) ? $_COOKIE['login_time'] : ""; 
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Resume Website</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="bg-layers">
        <div id="bg-home" class="bg-layer active"></div>
        <div id="bg-about" class="bg-layer"></div>
        <div id="bg-portfolio" class="bg-layer"></div>
        <div id="bg-contact" class="bg-layer"></div>
    </div>
    <div id="cursor-comet-container">
        <div class="comet-head"></div> <div class="comet-dot"></div>
        <div class="comet-dot"></div>
        <div class="comet-dot"></div>
        <div class="comet-dot"></div>
        <div class="comet-dot"></div>
        <div class="comet-dot"></div>
        <div class="comet-dot"></div>
        <div class="comet-dot"></div>
        <div class="comet-dot"></div>
        <div class="comet-dot"></div>
    </div>
    <!-- </div>
        <div class="nav-container">
            <ul class="nav-links">
                <li class="nav-item"><a href="#home">Home</a></li>
                <li class="nav-item"><a href="#about">About</a></li>
                <li class="nav-item"><a href="#portfolio">Portfolio</a></li>
                <li class="nav-item"><a href="#contact">Get in Touch</a></li>
            </ul>
        </div>
        <div class="user-actions">
            <?php if ($is_logged_in): ?>
                <span style="color: white; margin-right: 15px; display: inline-block; vertical-align: middle;">
                    歡迎，<?php echo htmlspecialchars($current_user); ?> <br>
                    <small style="color: #00ffff; font-size: 12px;">登入時間：<?php echo htmlspecialchars($login_time); ?></small>
                </span>
                
                <a href="change_password.php" class="nav-btn">修改密碼</a>
                <a href="logout.php" class="nav-btn">登出</a>
            <?php else: ?>
                <a href="login.html" class="nav-btn">登入</a>
                <a href="register.html" class="nav-btn">註冊</a>
            <?php endif; ?>
        </div> 
    </nav>  -->
    <nav>
        <div class="nav-left">
            <?php if ($is_logged_in): ?>
                <span class="nav-text">Login Time: <?php echo htmlspecialchars($login_time); ?></span>
            <?php endif; ?>
        </div>

        <div class="nav-center">
            <ul class="nav-links">
                <li class="nav-item"><a href="#home">Home</a></li>
                <li class="nav-item"><a href="#about">About</a></li>
                <li class="nav-item"><a href="#portfolio">Portfolio</a></li>
                <li class="nav-item"><a href="#contact">Get in Touch</a></li>
            </ul>
        </div>

        <div class="nav-right">
            <?php if ($is_logged_in): ?>
                <div class="dropdown">
                    <button class="nav-btn dropdown-toggle">
                        Hi, <?php echo htmlspecialchars($current_user); ?> ▼
                    </button>
                    <div class="dropdown-menu">
                        <a href="profile.php">Profile</a>
                        <a href="change_password.php">Change Password</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.html" class="nav-btn">Login</a>
                <a href="register.html" class="nav-btn">Register</a>
            <?php endif; ?>
        </div>
    </nav>


    <section id="home">
        <h1>你好，我是侯霈晴!</h1>
        <div class="typewriter-container">
            <p class="typewriter"></p>
        </div>
        
    </section>
    <section id="about">
        <div class="about-me">
            <img src="assets/images/profile.jpg" alt="Profile Picture" class="profile-pic">
            <div>
                <h2>About Me</h2>
                <p>我是侯霈晴，目前就讀於國立陽明交通大學資訊管理與財務金融學系資管組，<br>
                   熱衷於探索各種軟體與底層技術。</p>
            </div>
        </div>
        <div class="bio-panel fade-in-section">
            <h3>My Journey / 個人自傳</h3>
            <div class="bio-content">
                <p><strong>啟蒙與動機</strong></p>
                <p class="indent-text">在交大資財系的學習歷程中，我接觸了豐富的商業與資訊課程，並在不斷的實作中，確立了自己對「程式開發」最純粹的熱忱。特別是在修習投資學等金融課程後，我對「AI 交易」產生了濃厚的興趣。我深知未來的金融科技與數據決策，必須仰賴強大的機器學習與演算法支撐，這份認知成為我積極自學人工智慧與程式技術的強大動力。</p>
                
                <p><strong>學習歷程</strong></p>
                <p class="indent-text">為了將技術落地並驗證所學，我的程式學習歷程始終圍繞著「解決實際問題」。我曾主動開發整合雲端 LLM 的 AI 代理專案，實作出能自動化摘要信件與行程的應用，大幅深化了對 Python 與 API 串接的熟練度；此外，我也具備 Unity 2D 遊戲開發的 UI 實作經驗，並熟悉 C++ 與 C#。

                 在追求應用開發的同時，我意識到高效能運算與底層架構的重要性。因此，我參與了國網中心與清大合辦的「HPC x AI 高速計算人工智慧冬令營」，初步探索了平行運算的世界。為了進一步紮實學術基底，我目前正專注於線性代數、統計學、計算機結構與資料庫管理等核心課程，這些數理邏輯與系統架構的訓練，正是未來深入研究機器學習不可或缺的基石。

                除了技術追求，我也熱衷於團隊合作與知識傳遞。在擔任新化高中程式設計營講師的過程中，我學會將複雜的程式邏輯轉化為易懂的語言；而多益 960 分的英文能力，則確保我能無縫接軌國際最前沿的 AI 技術文獻。</p>

                <p><strong>未來展望</strong></p>
                <p class="indent-text">從資財系培養的金融敏銳度，到對程式開發與機器學習的狂熱，我的學習藍圖已十分清晰。未來，我將持續精進演算法與高效能運算技術，期許自己能將跨領域的商業思維與紮實的程式開發能力結合。我的目標是成為一名具備強大解決問題能力的 AI 工程師，在科技與金融的交匯處，持續發揮影響力並創造價值。</p>
            </div>
        </div>
        <div class="tech-stack">
            <h2>Tech Stack</h2>
            <div class="tech-icons-wrapper">
                <div class="tech-icons">
                    <img src="assets/images/icons/c++.png" alt="C++" class="tech-icon">
                    <img src="assets/images/icons/csharp.png" alt="Csharp" class="tech-icon">
                    <img src="assets/images/icons/python.png" alt="Python" class="tech-icon">
                    <img src="assets/images/icons/github.png" alt="GitHub" class="tech-icon">
                    <img src="assets/images/icons/docker.png" alt="Docker" class="tech-icon">
                    <img src="assets/images/icons/unity.png" alt="Unity" class="tech-icon">
                    <img src="assets/images/icons/php.png" alt="Php" class="tech-icon">      
                </div>
                <div class="tech-icons">
                    <img src="assets/images/icons/c++.png" alt="C++" class="tech-icon">
                    <img src="assets/images/icons/csharp.png" alt="Csharp" class="tech-icon">
                    <img src="assets/images/icons/python.png" alt="Python" class="tech-icon">
                    <img src="assets/images/icons/github.png" alt="GitHub" class="tech-icon">
                    <img src="assets/images/icons/docker.png" alt="Docker" class="tech-icon">
                    <img src="assets/images/icons/unity.png" alt="Unity" class="tech-icon">
                    <img src="assets/images/icons/php.png" alt="Php" class="tech-icon">      
                </div>
            </div>
        </div>
    </section>
    
    <section id="unity-showcase">
            <h2>Unity Project Showcase</h2>
            <p class="showcase-subtitle">Lobotomy Corporation - Twilight Branch</p>
            <div class="showcase-container">
                
                <div class="video-wrapper">
                    <video src="assets/videos/gamevideo.mp4" autoplay loop muted playsinline class="game-video"></video>
                </div>

                <div class="showcase-info">
                    <h3>2D 遊戲專案</h3>
                    <p>負責部分：UI 實作 (UI Implementation)</p>
                    <ul>
                        <li><strong>負責將 UI/UX 設計實際整合至 Unity 引擎中。</strong></li>
                        <li><strong>實作遊戲內的各項介面邏輯，包含：暫停選單 (Pause/Resume/Backpack)、背包介面選單 (武器、衣服選擇)、以及狀態選單 (道具選擇) 等。</strong></li>
                        <!-- 自己的經歷 -->
                    </ul>
                    <a href="https://www.youtube.com/watch?v=IVS1hpIdzno" target="_blank" class="watch-video-btn">
                        觀看完整遊戲預告影片 
                    </a>
                </div>
            </div>
        </section>


    <section id="portfolio">
        
        <div class="container">
            <h2>Portfolio</h2>
            <div class="tab-buttons">
                <button class="tab-btn active" data-target="projects-content">Projects</button>
                <button class="tab-btn" data-target="certificates-content">Certificates</button>
                <button class="tab-btn" data-target="traits-content">Traits</button>
            </div>

            <div id="projects-content" class="tab-content active">
                <div class="project-search">
                    <input type="text" id="project-search-input" placeholder="Search projects...">
                    <button id="project-search-btn">search</button> </div>
                <div class="project-list"></div>
            </div>

            <div id="certificates-content" class="tab-content">
                <div class="cert-grid"></div>
            </div>

            <div id="traits-content" class="tab-content">
                <div class="traits-container">
                    <div class="trait-card">
                        <span class="trait-dot"></span>
                        <p>心思細膩</p>
                    </div>
                    <div class="trait-card">
                        <span class="trait-dot"></span>
                        <p>邏輯清晰有條理</p>
                    </div>
                    <div class="trait-card">
                        <span class="trait-dot"></span>
                        <p>做事情全力以赴，不將就</p>
                    </div>
                </div>
            </div>
        </div> 
    </section>
    <section id="contact">
        <div class="contact-card">
            <h2>Get in Touch</h2>
            <div class="social-links">
                <a href="https://github.com/Karlaaaaa1212" target="_blank" class="social-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                </a>
                <a href="https://www.facebook.com/karla.hou.2025" target="_blank" class="social-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                <a href="https://www.instagram.com/karla1212.__/" target="_blank" class="social-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=karla.sc13@nycu.edu.tw" class="social-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                </a>
            </div>
            <form class="contact-form" action="https://formspree.io/f/xkoppqvg" method="POST">
                <input type="text" name="name" placeholder="Please enter your name" required>
                <input type="email" name="_replyto" placeholder="Please enter your email" required>
                <textarea name="message" placeholder="Please enter your message" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </section>

    <script src="assets/js/script.js"></script>

    <script>
        // 控制下拉選單：點擊展開，點擊外面關閉
        document.addEventListener("DOMContentLoaded", function() {
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            const dropdown = document.querySelector('.dropdown');

            if (dropdownToggle && dropdown) {
                // 點擊按鈕時，切換顯示/隱藏
                dropdownToggle.addEventListener('click', function(event) {
                    event.stopPropagation(); // 阻止事件冒泡，避免點擊馬上觸發 window 關閉
                    dropdown.classList.toggle('show');
                });
            }

            // 點擊網頁任何其他地方時，自動收起選單
            window.addEventListener('click', function() {
                if (dropdown && dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            });
        });
    </script>

</body>
</html>