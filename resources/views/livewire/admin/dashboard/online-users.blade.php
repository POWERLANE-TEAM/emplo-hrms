<section>
    <header class="fs-4 fw-bold mb-4" role="heading" aria-level="2">
        Online Users
    </header>
    
    <div
        x-data="{ onlineUsers: [] }"
        x-init="
            Echo.join('online-users')
                .here((users) => {
                    onlineUsers = users.filter(u => u.user_id !== {{ auth()->user()->user_id }});
                })
                .joining((user) => {
                    onlineUsers.push(user);
                })
                .leaving((user) => {
                    onlineUsers = onlineUsers.filter(u => u.user_id !== user.user_id);
                });
        "
    >
        <div class="d-flex">
            <ul class="list-unstyled">
                <template x-if="onlineUsers.length > 0">
                    <template x-for="user in onlineUsers" :key="user.user_id">
                        <li>
                            <x-online-status status="online">
                                <img :src="user.photo" alt="user" class="img-fluid rounded-circle" width="35" height="35" />
                            </x-online-status>                           
                            <span class="ps-1" x-text="user.email"></span>    
                        </li>
                    </template>
                </template>

                <template x-if="onlineUsers.length === 0">
                    <li>No online people</li>
                </template>
            </ul>
        </div>
    </div>  
</section>
