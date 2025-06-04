function closeWinPopup() {
    document.getElementById('windowsPopup').style.display = 'none';
    document.getElementById('winPopupOverlay').style.display = 'none';
    history.replaceState({}, document.title, window.location.pathname);
}

document.addEventListener('DOMContentLoaded', function() {
   
    const productImages = document.querySelectorAll('.product-image img');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                    }
                    imageObserver.unobserve(img);
                }
            });
        });

        productImages.forEach(img => {
            if (img.complete) return;
            imageObserver.observe(img);
        });
    }

    function equalizeProductCardHeights() {
        const productSections = document.querySelectorAll('.products-grid, .products-carousel');
        
        productSections.forEach(section => {
            const cards = section.querySelectorAll('.product-card');
            let maxHeight = 0;
            
            cards.forEach(card => card.style.height = 'auto');
            
            cards.forEach(card => {
                const height = card.offsetHeight;
                if (height > maxHeight) {
                    maxHeight = height;
                }
            });
        
            if (maxHeight > 0 && window.innerWidth > 768) {
                cards.forEach(card => card.style.height = maxHeight + 'px');
            }
        });
    }
    
    equalizeProductCardHeights();
    window.addEventListener('resize', equalizeProductCardHeights);
    
    const animateOnScroll = () => {
        const sections = document.querySelectorAll('.section');
        
        sections.forEach(section => {
            const sectionTop = section.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (sectionTop < windowHeight * 0.75) {
                section.classList.add('fade-in');
            }
        });
    };
    
    const style = document.createElement('style');
    style.textContent = `
        .section {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .section.fade-in {
            opacity: 1;
            transform: translateY(0);
        }
    `;
    document.head.appendChild(style);
    
    animateOnScroll();
    window.addEventListener('scroll', animateOnScroll);
});