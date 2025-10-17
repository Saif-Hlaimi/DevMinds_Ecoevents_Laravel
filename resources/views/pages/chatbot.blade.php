<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EcoEvents</title>
    <style>
        /* Chatbot flottant */
        #chatbot { position: fixed; bottom: 20px; right: 25px; font-family: Arial, sans-serif; z-index: 9999; }
        #chatbot-icon { background: #2b8a3e; color: #fff; border-radius: 50%; width: 55px; height: 55px; text-align: center; line-height: 55px; cursor: pointer; font-size: 24px; box-shadow: 0 3px 8px rgba(0,0,0,0.3); }
        #chatbot-container { width: 320px; background: #fff; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); display: flex; flex-direction: column; overflow: hidden; display: none; }
        .chat-header { background: #2b8a3e; color: white; padding: 10px; display: flex; justify-content: space-between; font-weight: bold; }
        #chat-window { height: 320px; overflow-y: auto; padding: 10px; }
        .user-msg, .bot-msg { margin: 8px 0; padding: 8px 12px; border-radius: 8px; max-width: 80%; }
        .user-msg { background: #e1f5e6; align-self: flex-end; text-align: right; }
        .bot-msg { background: #f0f0f0; align-self: flex-start; }
        .chat-input { display: flex; border-top: 1px solid #ddd; }
        .chat-input input { flex: 1; border: none; padding: 10px; outline: none; }
        .chat-input button { background: #2b8a3e; color: white; border: none; padding: 10px 15px; cursor: pointer; }
    </style>
</head>
<body>
<div id="chatbot">
    <div id="chatbot-icon">🤖</div>
    <div id="chatbot-container">
        <div class="chat-header">
            Chatbot EcoEvents
            <span id="chatbot-close">✖</span>
        </div>
        <div id="chat-window"></div>
        <div class="chat-input">
            <input type="text" id="chat-input-text" placeholder="Écrire un message...">
            <button id="chat-send">Envoyer</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const icon = document.getElementById('chatbot-icon');
    const container = document.getElementById('chatbot-container');
    const closeBtn = document.getElementById('chatbot-close');
    const sendBtn = document.getElementById('chat-send');
    const input = document.getElementById('chat-input-text');
    const chatWindow = document.getElementById('chat-window');

    icon.addEventListener('click', () => container.style.display = container.style.display === 'flex' ? 'none' : 'flex');
    closeBtn.addEventListener('click', () => container.style.display = 'none');

    const appendMessage = (sender, text) => {
        const div = document.createElement('div');
        div.className = sender === 'user' ? 'user-msg' : 'bot-msg';
        div.textContent = text;
        chatWindow.appendChild(div);
        chatWindow.scrollTop = chatWindow.scrollHeight;
    };

    const sendMessage = async () => {
        const text = input.value.trim();
        if (!text) return;
        appendMessage('user', text);
        input.value = '';
        appendMessage('bot', '⏳ Réponse en cours...');

        try {
            const response = await fetch('{{ route("chatbot.ask") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: text })
            });

            const data = await response.json();
            chatWindow.lastChild.remove();
            appendMessage('bot', data.reply || data.error || 'Erreur de communication.');
        } catch (err) {
            chatWindow.lastChild.remove();
            appendMessage('bot', 'Erreur de communication avec le serveur.');
        }
    };

    sendBtn.addEventListener('click', sendMessage);
    input.addEventListener('keydown', (e) => { if (e.key === 'Enter') sendMessage(); });
});
</script>
</body>
</html>
