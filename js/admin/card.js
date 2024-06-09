document.addEventListener('DOMContentLoaded', () => {
    const openPopup = (popupId) => {
        const popup = document.getElementById(popupId);
        if (popup) {
            popup.style.display = 'block';
            setTimeout(() => popup.classList.add('open'), 10);
        }
    };

    const closePopup = (popup) => {
        popup.classList.remove('open');
        setTimeout(() => popup.style.display = 'none', 300);
    };

    document.querySelectorAll('.card, .aj').forEach(element => {
        element.addEventListener('click', (event) => {
            const popupId = event.currentTarget.getAttribute('data-popup-id');
            const tdNumber = event.currentTarget.getAttribute('data-td-number');
            if (tdNumber) {
                fetch(`td1.php?td_number=${tdNumber}`)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('td-popup-content').innerHTML = data;
                        openPopup(popupId);
                    });
            } else {
                openPopup(popupId);
            }
        });
    });

    document.querySelectorAll('.card-btn').forEach(element => {
        element.addEventListener('click', (event) => {
            event.stopPropagation();
            const popupId = event.currentTarget.getAttribute('data-popup-id');
            openPopup(popupId);
        });
    });

    document.querySelectorAll('.popup-close').forEach(closeBtn => {
        closeBtn.addEventListener('click', () => {
            const popup = closeBtn.closest('.popup');
            closePopup(popup);
        });
    });

    window.addEventListener('click', (event) => {
        document.querySelectorAll('.popup').forEach(popup => {
            if (event.target === popup) {
                closePopup(popup);
            }
        });
    });
});


