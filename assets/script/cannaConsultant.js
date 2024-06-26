import {marked} from 'marked';
import {sanitizeHtml} from "bootstrap/js/src/util/sanitizer";

document.addEventListener('DOMContentLoaded', function() {
    let chat = document.getElementById('chat');
    let chatSend = document.getElementById('chat-send');
    let formChat = document.getElementById('form-chat');
    let chatInput = document.getElementById('chat-input');

    formatChatMessages();
    scrollToBottom();

    formChat.addEventListener('submit', function(event) {
        event.preventDefault();
        submitForm();
    });

    chatInput.addEventListener("keydown", function(e) {
        if (e.key === "Enter" && e.shiftKey) {
            e.preventDefault();
            submitForm();
        }

        chatInput.style.height = "auto";
        chatInput.style.height = `${chatInput.scrollHeight}px`;

        if (chatInput.scrollHeight > 150) {
            chatInput.style.overflowY = "scroll";
            chatInput.style.height = "150px";
        } else {
            chatInput.style.overflowY = "hidden";
        }
    });

    function submitForm() {
        if (chatInput.value) {
            const chatInput_value = chatInput.value;
            chatInput.value = '';
            chatSend.disabled = true;

            createTextBubbleUser(chatInput_value);
            createLoadingBubble();
            scrollToBottom();

            fetch('/canna-consultant/add-message?message=' + chatInput_value, {
                method: 'POST',
            }).then(r => r.json()).then(data => {
                removeLoadingBubble();
                if (!data.error) {
                    createTextBubbleBot(data);
                } else {
                    createErrorAlarm(data.error);
                }
                scrollToBottom();
                chatSend.disabled = false;
            });
        }
    }

    function scrollToBottom() {
        chat.scrollTop = chat.scrollHeight;
    }

    function createErrorAlarm(error) {
        let outerDiv = document.createElement('div');
        outerDiv.classList.add('row', 'mb-3');

        let innerDiv = document.createElement('div');
        innerDiv.classList.add('col-10');

        let rowDiv = document.createElement('div');
        rowDiv.classList.add('row');

        let cardDiv = document.createElement('div');
        cardDiv.classList.add('alert', 'alert-danger');

        let pElement = document.createElement('p');
        pElement.classList.add('mb-0');
        pElement.textContent = sanitizeHtml("Es ist ein fehler aufgetreten. Versuche es später erneut. Fehler: " + error);

        let spaceRight = document.createElement('div');
        spaceRight.classList.add('col-1');

        let spaceLeft = document.createElement('div');
        spaceLeft.classList.add('col-1');

        let roundedDiv = document.createElement('div');
        roundedDiv.classList.add('rounded-5');
        roundedDiv.style.width = '50px';
        roundedDiv.style.height = '50px';
        roundedDiv.style.backgroundColor = '#656003';

        let pElement2 = document.createElement('p');
        pElement2.classList.add('mb-0');
        pElement2.style.padding = '12px 0';
        pElement2.style.textAlign = 'center';
        pElement2.textContent = chat.dataset.userinitials;


        outerDiv.appendChild(spaceLeft);
        cardDiv.appendChild(pElement);
        rowDiv.appendChild(cardDiv);
        innerDiv.appendChild(rowDiv);
        outerDiv.appendChild(innerDiv);
        outerDiv.appendChild(spaceRight);

        chat.appendChild(outerDiv);
    }

    function createTextBubbleUser(message) {
        let outerDiv = document.createElement('div');
        outerDiv.classList.add('row', 'd-flex', 'justify-content-end', 'mb-3');

        let innerDiv = document.createElement('div');
        innerDiv.classList.add('col-10');

        let rowDiv = document.createElement('div');
        rowDiv.classList.add('row');

        let cardDiv = document.createElement('div');
        cardDiv.classList.add('card', 'mb-2');

        let cardBodyDiv = document.createElement('div');
        cardBodyDiv.classList.add('card-body');

        let userInfo = document.createElement('h5');
        userInfo.classList.add('d-flex', 'align-items-center', 'small');

        let userImageDiv = document.createElement('div');
        userImageDiv.classList.add('rounded-circle', 'me-2');
        userImageDiv.style.background = `url(${chat.dataset.userphotopath})`;
        userImageDiv.style.height = '20px';
        userImageDiv.style.width = '20px';
        userImageDiv.style.backgroundRepeat = 'no-repeat';
        userImageDiv.style.backgroundSize = 'cover';
        userImageDiv.style.backgroundPosition = 'center';

        let usernameText = document.createTextNode(chat.dataset.username);
        userInfo.appendChild(userImageDiv);
        userInfo.appendChild(usernameText);

        let pElement = document.createElement('p');
        pElement.classList.add('mb-0');
        pElement.textContent = message;

        cardBodyDiv.appendChild(userInfo);
        cardBodyDiv.appendChild(pElement);
        cardDiv.appendChild(cardBodyDiv);
        rowDiv.appendChild(cardDiv);
        innerDiv.appendChild(rowDiv);
        outerDiv.appendChild(innerDiv);

        let imageDiv = document.createElement('div');
        imageDiv.classList.add('col-auto');
        outerDiv.appendChild(imageDiv);

        chat.appendChild(outerDiv);
    }

    function createTextBubbleBot(data) {
        const message = data.data[0].content[0].text.value;
        const messageHTML = marked(message);

        let outerDiv = document.createElement('div');
        outerDiv.classList.add('row', 'd-flex', 'justify-content-start', 'mb-3');

        let emptyDiv = document.createElement('div');
        emptyDiv.classList.add('col-auto');

        let innerDiv = document.createElement('div');
        innerDiv.classList.add('col-10');

        let rowDiv = document.createElement('div');
        rowDiv.classList.add('row');

        let cardDiv = document.createElement('div');
        cardDiv.classList.add('card', 'mb-2');

        let cardBodyDiv = document.createElement('div');
        cardBodyDiv.classList.add('card-body');

        let userInfo = document.createElement('h5');
        userInfo.classList.add('d-flex', 'align-items-center', 'small');

        let userImageDiv = document.createElement('div');
        userImageDiv.classList.add('rounded-circle', 'me-2');
        userImageDiv.style.background = `url('/build/images/weedwizrad_wizard.jpg')`;
        userImageDiv.style.height = '20px';
        userImageDiv.style.width = '20px';
        userImageDiv.style.backgroundRepeat = 'no-repeat';
        userImageDiv.style.backgroundSize = 'cover';
        userImageDiv.style.backgroundPosition = 'center';

        let usernameText = document.createTextNode('WeedWizard Canna Consultant');
        userInfo.appendChild(userImageDiv);
        userInfo.appendChild(usernameText);

        let pElement = document.createElement('p');
        pElement.classList.add('mb-0', 'assistant-message');
        pElement.innerHTML = sanitizeHtml(messageHTML);

        cardBodyDiv.appendChild(userInfo);
        cardBodyDiv.appendChild(pElement);
        cardDiv.appendChild(cardBodyDiv);
        rowDiv.appendChild(cardDiv);
        innerDiv.appendChild(rowDiv);
        outerDiv.appendChild(emptyDiv);
        outerDiv.appendChild(innerDiv);

        chat.appendChild(outerDiv);
    }

    function createLoadingBubble() {
        let outerDiv = document.createElement('div');
        outerDiv.classList.add('row', 'd-flex', 'justify-content-start', 'mb-3');
        outerDiv.id = 'loading';

        let emptyDiv = document.createElement('div');
        emptyDiv.classList.add('col-auto');

        let innerDiv = document.createElement('div');
        innerDiv.classList.add('col-10');

        let rowDiv = document.createElement('div');
        rowDiv.classList.add('row');

        let cardDiv = document.createElement('div');
        cardDiv.classList.add('card', 'mb-2');

        let cardBodyDiv = document.createElement('div');
        cardBodyDiv.classList.add('card-body');

        let userInfo = document.createElement('h5');
        userInfo.classList.add('d-flex', 'align-items-center', 'small');

        let userImageDiv = document.createElement('div');
        userImageDiv.classList.add('rounded-circle', 'me-2', 'mb-2');
        userImageDiv.style.background = `url('/build/images/weedwizrad_wizard.jpg')`;
        userImageDiv.style.height = '20px';
        userImageDiv.style.width = '20px';
        userImageDiv.style.backgroundRepeat = 'no-repeat';
        userImageDiv.style.backgroundSize = 'cover';
        userImageDiv.style.backgroundPosition = 'center';

        let usernameText = document.createTextNode('WeedWizard Canna Consultant');
        userInfo.appendChild(userImageDiv);
        userInfo.appendChild(usernameText);

        let pElement = document.createElement('div');
        pElement.classList.add('mb-0', 'loading');

        cardBodyDiv.appendChild(userInfo);
        cardBodyDiv.appendChild(pElement);
        cardDiv.appendChild(cardBodyDiv);
        rowDiv.appendChild(cardDiv);
        innerDiv.appendChild(rowDiv);
        outerDiv.appendChild(emptyDiv);
        outerDiv.appendChild(innerDiv);

        chat.appendChild(outerDiv);
    }

    function removeLoadingBubble() {
        let loading = document.getElementById('loading');
        loading.remove();
    }

    function formatChatMessages() {
        let assistantMessages = document.querySelectorAll('.assistant-message');

        [...assistantMessages].forEach(function(message) {
            message.innerHTML = marked(message.textContent);
        });
    }
});
