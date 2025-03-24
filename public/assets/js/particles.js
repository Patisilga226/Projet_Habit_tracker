// Particles Animation Script

document.addEventListener('DOMContentLoaded', function() {
  // Create particles in container
  function createParticles() {
    const container = document.querySelector('.particles-container');
    if (!container) return;
    
    // Clear existing particles
    container.innerHTML = '';
    
    // Create new particles
    const particleCount = window.innerWidth < 768 ? 30 : 60;
    const colors = ['#4F46E5', '#818CF8', '#10B981', '#7C3AED', '#FFFFFF'];
    
    for (let i = 0; i < particleCount; i++) {
      const particle = document.createElement('div');
      particle.classList.add('particle');
      
      // Random properties
      const size = Math.random() * 10 + 3;
      const color = colors[Math.floor(Math.random() * colors.length)];
      const left = Math.random() * 100;
      const delay = Math.random() * 15;
      const duration = Math.random() * 20 + 10;
      
      // Apply styles
      particle.style.width = `${size}px`;
      particle.style.height = `${size}px`;
      particle.style.backgroundColor = color;
      particle.style.left = `${left}%`;
      particle.style.animationDuration = `${duration}s`;
      particle.style.animationDelay = `${delay}s`;
      
      container.appendChild(particle);
    }
  }
  
  // Initialize particles
  createParticles();
  
  // Recreate particles on window resize
  window.addEventListener('resize', createParticles);
  
  // Typing effect
  function initTypingEffect() {
    const elements = document.querySelectorAll('.typing-text');
    
    elements.forEach(element => {
      const text = element.getAttribute('data-text');
      if (!text) return;
      
      element.textContent = '';
      element.classList.add('typing-effect');
      
      let index = 0;
      const speed = element.getAttribute('data-speed') || 100;
      
      function typeChar() {
        if (index < text.length) {
          element.textContent += text.charAt(index);
          index++;
          setTimeout(typeChar, speed);
        } else {
          element.classList.remove('typing-effect');
        }
      }
      
      // Start typing when element is in viewport
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            setTimeout(() => {
              typeChar();
            }, 500);
            observer.unobserve(entry.target);
          }
        });
      });
      
      observer.observe(element);
    });
  }
  
  // Initialize typing effect
  initTypingEffect();
  
  // 3D Card Effect
  function init3DCards() {
    const cards = document.querySelectorAll('.card-3d');
    
    cards.forEach(card => {
      card.addEventListener('mousemove', e => {
        const cardRect = card.getBoundingClientRect();
        const cardCenterX = cardRect.left + cardRect.width / 2;
        const cardCenterY = cardRect.top + cardRect.height / 2;
        
        const mouseX = e.clientX - cardCenterX;
        const mouseY = e.clientY - cardCenterY;
        
        // Calculate rotation based on mouse position
        const rotateY = mouseX / 10;
        const rotateX = -mouseY / 10;
        
        card.querySelector('.card-3d-inner').style.transform = 
          `rotateY(${rotateY}deg) rotateX(${rotateX}deg)`;
      });
      
      card.addEventListener('mouseleave', () => {
        card.querySelector('.card-3d-inner').style.transform = 
          'rotateY(0deg) rotateX(0deg)';
      });
    });
  }
  
  // Initialize 3D cards
  init3DCards();
  
  // Reveal animations on scroll
  function initRevealAnimations() {
    const elements = document.querySelectorAll('.reveal-left, .reveal-right, .reveal-top, .reveal-bottom');
    
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.animationPlayState = 'running';
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });
    
    elements.forEach(element => {
      element.style.animationPlayState = 'paused';
      observer.observe(element);
    });
  }
  
  // Initialize reveal animations
  initRevealAnimations();
  
  // Counter animation
  function initCounters() {
    const counters = document.querySelectorAll('.counter');
    
    counters.forEach(counter => {
      const target = parseInt(counter.getAttribute('data-target'));
      const duration = parseInt(counter.getAttribute('data-duration')) || 2000;
      const step = target / (duration / 16);
      let current = 0;
      
      const updateCounter = () => {
        current += step;
        if (current < target) {
          counter.textContent = Math.floor(current);
          requestAnimationFrame(updateCounter);
        } else {
          counter.textContent = target;
        }
      };
      
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            updateCounter();
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.5 });
      
      observer.observe(counter);
    });
  }
  
  // Initialize counters
  initCounters();
  
  // Parallax effect
  function initParallax() {
    const elements = document.querySelectorAll('.parallax');
    
    window.addEventListener('scroll', () => {
      elements.forEach(element => {
        const speed = element.getAttribute('data-speed') || 0.5;
        const yPos = -(window.scrollY * speed);
        element.style.transform = `translateY(${yPos}px)`;
      });
    });
  }
  
  // Initialize parallax
  initParallax();
});