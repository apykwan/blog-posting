<div x-data="{isOpen: false}">
    <span
        x-on:click="isOpen = true; document.querySelector('.chat-field').focus();" 
        class="text-white mr-2 header-chat-icon" 
        title="Chat" 
        data-toggle="tooltip" 
        data-placement="bottom"
    >
        <i class="fas fa-comment"></i>
    </span>

    <div 
        data-username="{{Auth::user()->username}}"
        data-avatar="{{Auth::user()->avatar}}" 
        id="chat-wrapper"
        x-bind:class="isOpen ? 'chat--visible' : ''"  
        class="chat-wrapper chat-wrapper--ready shadow border-top border-left border-right"
    >
        <div class="chat-title-bar">
            Chat 
            <span x-on:click="isOpen = false" class="chat-title-bar-close">
                <i class="fas fa-times-circle"></i>
            </span>
        </div>

        <div id="chat" class="chat-log">
            @if (count($chatLog) > 0)
                @foreach ($chatLog as $chat)
                    @if ($chat['selfmessage'] == true)
                        <div class="chat-self">
                            <div class="chat-message">
                                <div class="chat-message-inner">{{$chat['textvalue']}}</div>
                            </div>
                            <img class="chat-avatar avatar-tiny" src="{{$chat['avatar']}}">
                        </div>
                    @else 
                        <div class="chat-other">
                            <a href="/profile/{{$chat['username']}}>
                                <img class="avatar-tiny" src="{{$chat['avatar']}}">
                            </a>
                            <div class="chat-message">
                                <div class="chat-message-inner">
                                    <a href="/profile/{{$chat['username']}}">
                                        <strong>{{$chat['username']}}:</strong>
                                    </a>
                                    {{$chat['textvalue']}}
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
        
        <form wire:submit="send" id="chatForm" class="chat-form border-top">
            @csrf
            <input wire:model.live="textvalue" type="text" class="chat-field" id="chatField" placeholder="Type a message…" autocomplete="off">
        </form>
    </div>
    
    <script>
        document.addEventListener('livewire:init', () => {
            const socket = io('http://localhost:5001');

            socket.on('chatMessage', (data) => {
                console.log('📨 Received from socket:', data);

                const componentEl = document.querySelector('[wire\\:id]');
                if (componentEl) {
                    componentEl.dispatchEvent(
                        new CustomEvent('newMessageFromSocket', {
                            detail: data,
                            bubbles: true,
                        })
                    );
                } else {
                    console.warn('⚠️ Could not find Livewire component root');
                }
            });

            socket.on('connect_error', (error) => {
                console.error('Socket.IO connection error:', error);
            });
        });
    </script>


</div>

