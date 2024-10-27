BX.ready(function () {
    // Check if BXTIMEMAN object exists
    if (typeof BXTIMEMAN !== 'undefined') {
        // Save the original start method
        var originalStart = BXTIMEMAN.start;

        // Override the start method
        BXTIMEMAN.start = function () {
            // Show the modal window instead of starting the workday immediately
            showModalWindow();
        };

        function showModalWindow() {
            var modalContent = BX.create('div', {
                props: { className: 'custom-modal-content' },
                children: [
                    BX.create('p', {
                        text: 'Добро пожаловать! Нажмите кнопку ниже, чтобы начать рабочий день.',
                    }),
                    BX.create('button', {
                        props: { className: 'ui-btn ui-btn-primary' },
                        text: 'Начать рабочий день',
                        events: {
                            click: function () {
                                modal.close();
                                // Call the original start method
                                originalStart.apply(BXTIMEMAN);
                            },
                        },
                    }),
                ],
            });

            var modal = new BX.PopupWindow('customModal', null, {
                content: modalContent,
                width: 400,
                height: 150,
                closeIcon: { right: '20px', top: '20px' },
                titleBar: 'Подтверждение начала рабочего дня',
                closeByEsc: true,
                overlay: { backgroundColor: 'black', opacity: '80' },
                events: {
                    onPopupClose: function () {
                        // Do nothing; the workday won't start unless the button is clicked
                    },
                },
            });

            modal.show();
        }
    }
});
