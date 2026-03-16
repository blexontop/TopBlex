// Simple interactive helpers for product pages
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.product-thumb').forEach((thumb) => {
    thumb.addEventListener('click', (e) => {
      const src = e.currentTarget.dataset.src || e.currentTarget.getAttribute('src');
      const main = document.querySelector('.product-main-img');
      if (src && main) main.src = src;
    });
  });
});
import './bootstrap';
