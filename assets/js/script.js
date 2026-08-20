
function attachProjectClickListeners() {
    const projectItems = document.querySelectorAll(".project-item");
    projectItems.forEach(item => {
        item.addEventListener("click", function() {
            const url = this.getAttribute("data-url");
            if (url) {
                window.open(url, "_blank");
            }
        });
    });
}

// Typewriter effect
const typewriterElement = document.querySelector(".typewriter");
const texts = ["Information Management & Finance Student.", "C++ & Python Developer.", "High-Performance Computing (HPC) Learner.", "Currently walking with Pikmin Bloom."];
let textIndex = 0;
let isDeleting = false;

// script.js

function typeWriter() {
    const currentText = texts[textIndex];
    
    if (!isDeleting) {
        // --- 正在打字 ---
        let charIndex = 0;
        typewriterElement.textContent = '';
        
        const typeNextChar = () => {
            if (charIndex < currentText.length) {
                typewriterElement.textContent += currentText[charIndex];
                charIndex++;
                
                // 🌟 新增：打字時加入震動類別，增加動態感
                typewriterElement.classList.add('typing-active');
                setTimeout(() => typewriterElement.classList.remove('typing-active'), 50);

                // 🌟 核心優化：隨機打字速度 (80ms~150ms)，更有節奏感
                const randomSpeed = Math.random() * (150 - 80) + 80;
                setTimeout(typeNextChar, randomSpeed);
            } else {
                // 打完一串字後的停頓
                setTimeout(() => {
                    isDeleting = true;
                    typeWriter();
                }, 2000); // 停頓 2 秒
            }
        };
        typeNextChar();
        
    } else {
        // --- 正在刪除 ---
        let charIndex = currentText.length;
        
        const deleteNextChar = () => {
            if (charIndex > 0) {
                typewriterElement.textContent = currentText.substring(0, charIndex - 1);
                charIndex--;
                // 刪除速度可以快一點且固定
                setTimeout(deleteNextChar, 50);
            } else {
                isDeleting = false;
                textIndex = (textIndex + 1) % texts.length;
                setTimeout(typeWriter, 500); // 切換下一句前的短暫停頓
            }
        };
        deleteNextChar();
    }
}

// Start the typewriter effect
typeWriter();

const projects = [
    {
        "title": "Lobotomy Corporation - Twilight Branch",
        "description": "本專案為以月亮計畫 (Project Moon) 旗下遊戲《腦葉公司》世界觀為基礎的二次創作 (Fanmade) 遊戲。",
        "date": "2025/12/26",
        "url": "https://github.com/Karlaaaaa1212/Unity-2D-Game---Lobotomy-Corporation-Twilight-Branch"
    },
    {
        "title": "LLM Assistant",
        "description": "結合 OpenAI API 與 Google Workspace (Gmail & Calendar) 的 Telegram 個人助理機器人。",
        "date": "2026/2/10",
        "url": "https://github.com/Karlaaaaa1212/LLM-Assistant"
    }
]
const projectsList = document.querySelector(".project-list");

function renderProjects(list) {
    projectsList.innerHTML = list
        .map(p => {
            return `
            <div class="project-item" data-url="${p.url}" target="_blank">
                <div class="content">
                    <h3>${p.title}</h3>
                    <p>${p.description.replace(/\n/g, "<br>")}</p>
                    <p class="meta">Created on ${p.date}</p>
                </div>
                <div class="github-hint">View on GitHub ➔</div>
            </div>
            `;
        })
        .join("");
    attachProjectClickListeners();
}
// first time load all projects
renderProjects(projects);

// Search functionality
const searchInput = document.getElementById("project-search-input");
const searchBtn = document.getElementById("project-search-btn");

function searchProjects() {
    const searchTerm = searchInput.value.toLowerCase();
    const filteredProjects = projects.filter(project =>
        project.title.toLowerCase().includes(searchTerm)
    );
    renderProjects(filteredProjects);
}

searchBtn.addEventListener("click", searchProjects);
searchInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") {
        searchProjects();
    }
});

/* Scroll Navigation */
const sections = document.querySelectorAll("section");
const navLinks = document.querySelectorAll(".nav-item a");

function updateActiveNav() {
    let currentSection = "";
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;

        if (window.scrollY >= sectionTop - 100) {
            currentSection = section.getAttribute("id");
        }
    });
    
    navLinks.forEach(link => {
        link.classList.remove("active");
        if (link.getAttribute("href") === `#${currentSection}`) {
            link.classList.add("active");
        }
    });
}

window.addEventListener("scroll", updateActiveNav);
updateActiveNav();



/* Fade-in & out + background*/
const observerOptions = {
    threshold: 0.3,
    rootMargin: "0px"
};

const bgLayers = {
    'home': document.getElementById('bg-home'),
    'about': document.getElementById('bg-about'),
    'portfolio': document.getElementById('bg-portfolio'),
    'traits-content': document.getElementById('bg-portfolio'),
    'contact': document.getElementById('bg-contact'),
    'unity-showcase': document.getElementById('bg-about') // 可以跟 about 用同一個
};

const sectionObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add("visible");
            // 🌟 核心邏輯：換背景
            const id = entry.target.getAttribute("id");
            if (bgLayers[id]) {
                // 先關掉所有背景的 active
                Object.values(bgLayers).forEach(layer => layer.classList.remove('active'));
                // 開啟當前區 aerial 背景
                bgLayers[id].classList.add('active');
            }
        } else {
            entry.target.classList.remove("visible");
        }
    });
}, observerOptions);

sections.forEach(section => {
    sectionObserver.observe(section);
});

const fadeInSections = document.querySelectorAll(".fade-in-section");
fadeInSections.forEach(section => {
    sectionObserver.observe(section);
});


const tabBtns = document.querySelectorAll('.tab-btn');
const tabContents = document.querySelectorAll('.tab-content');

tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        // 1. 移除所有按鈕的 active 狀態
        tabBtns.forEach(b => b.classList.remove('active'));
        // 2. 移除所有內容區塊的 active 狀態
        tabContents.forEach(c => c.classList.remove('active'));

        // 3. 把被點擊的按鈕加上 active
        btn.classList.add('active');
        
        // 4. 找到對應的內容區塊，並加上 active 讓它顯示出來
        const targetId = btn.getAttribute('data-target');
        document.getElementById(targetId).classList.add('active');
    });
});

/* =========================================
   新增：Certificates 資料與渲染邏輯
   ========================================= */

// 你的作品/證照資料庫
const certificatesData = [
    {
        imageUrl: "assets/images/certificates/c1.png", // 請換成你實際的圖片檔名
        caption: "人工智慧素養國際認證-專業級"
    },
    {
        imageUrl: "assets/images/certificates/c2.png", 
        caption: "SSE AI Prompt Engineer 國際證照"
    },
    {
        imageUrl: "assets/images/certificates/c3.png",
        caption: "ICDL IT Security 國際認證"
    },
    {
        imageUrl: "assets/images/certificates/c4.png",
        caption: "多益證書"
    },
    {
        imageUrl: "assets/images/certificates/c5.png",
        caption: "全民英檢中高級證書"
    },
    {
        imageUrl: "assets/images/certificates/c6.png",
        caption: "電子商務分析師乙級證書"
    },
    {
        imageUrl: "assets/images/certificates/c7.png",
        caption: "新化高中「程式設計營」講師證明"
    },
    {
        imageUrl: "assets/images/certificates/c8.png",
        caption: "新化高中「程式設計營 Part 2」講師證明"
    },
    {
        imageUrl: "assets/images/certificates/c9.png",
        caption: "HPC x AI 2026 高速計算人工智慧冬令營結業證書"
    },
    {
        imageUrl: "assets/images/certificates/c10.png",
        caption: "SSE 2025 人工智慧 AI Prompt Engineer 國際證照種子教師研習營證書"
    }
    
];

const certGrid = document.querySelector(".cert-grid");

// 渲染卡片的函數
function renderCertificates() {
    if (!certGrid) return;
    
    certGrid.innerHTML = certificatesData.map(cert => {
        return `
        <div class="cert-card">
            <div class="cert-img-wrapper">
                <img src="${cert.imageUrl}" alt="${cert.caption}">
            </div>
            <p class="cert-caption">${cert.caption}</p>
        </div>
        `;
    }).join("");
}

// 執行渲染
renderCertificates();



/* =========================================
   彗星尾跟隨邏輯 - 動態縮放與重延遲版
   ========================================= */

const cometContainer = document.getElementById('cursor-comet-container');
const cometHead = cometContainer.querySelector('.comet-head');
const cometDots = cometContainer.querySelectorAll('.comet-dot');

const cometPoints = [];

// A) 初始化首顆球 (頭)
cometPoints.push({
    el: cometHead,
    targetX: 0, targetY: 0,
    currentX: 0, currentY: 0
});

// B) 🌟 動態初始化尾巴：讓尺寸依序遞減
cometDots.forEach((dot, index) => {
    // 尺寸從 20px 開始，每顆減少 1.5px
    const size = 20 - (index * 1.5); 
    dot.style.width = size + 'px';
    dot.style.height = size + 'px';
    
    // 透明度也依序遞減，讓尾巴更有消散感
    dot.style.opacity = 1 - (index * 0.08);

    cometPoints.push({
        el: dot,
        targetX: 0, targetY: 0,
        currentX: 0, currentY: 0
    });
});

// 🌟 核心參數：延遲系數。值越小，滑起來越延遲 (0.05 非常滑溜)
const headDelayFactor = 0.05; 
let mouseX = 0;
let mouseY = 0;

window.addEventListener('mousemove', (e) => {
    mouseX = e.clientX;
    mouseY = e.clientY;
    cometPoints[0].targetX = mouseX;
    cometPoints[0].targetY = mouseY;
});

function animateCometTrail() {
    // 處理頭部
    const head = cometPoints[0];
    head.currentX += (head.targetX - head.currentX) * headDelayFactor;
    head.currentY += (head.targetY - head.currentY) * headDelayFactor;
    head.el.style.left = head.currentX + 'px';
    head.el.style.top = head.currentY + 'px';

    // 處理鏈式尾巴 (每一顆追著前一顆跑)
    for (let i = 1; i < cometPoints.length; i++) {
        const p = cometPoints[i];
        const prev = cometPoints[i - 1];
        
        // 尾巴點的跟隨系數固定在 0.5，保證連貫性
        p.currentX += (prev.currentX - p.currentX) * 0.5;
        p.currentY += (prev.currentY - p.currentY) * 0.5;

        p.el.style.left = p.currentX + 'px';
        p.el.style.top = p.currentY + 'px';
    }

    requestAnimationFrame(animateCometTrail);
}

animateCometTrail();

// 互動偵測：按鈕與連結
const interactiveElements = document.querySelectorAll('a, button, .project-item, .cert-card');
interactiveElements.forEach(el => {
    el.addEventListener('mouseenter', () => document.body.classList.add('cursor-hover'));
    el.addEventListener('mouseleave', () => document.body.classList.remove('cursor-hover'));
});