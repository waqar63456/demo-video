// accordion.js

export default function initializeAccordion() {
    const cards = document.querySelectorAll('.cardseo');

    cards.forEach(card => {
      card.addEventListener('show.bs.collapse', function () {
        // Close other open questions
        cards.forEach(otherCard => {
          if (otherCard !== card) {
            const collapse = bootstrap.Collapse.getInstance(otherCard.querySelector('.collapse'));
            if (collapse && collapse._isShown) {
              collapse.hide();
            }
          }
        });
      });
    });
  }
