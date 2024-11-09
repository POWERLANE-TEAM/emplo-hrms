let onlineUsers = [];

Echo.join('online-users')
    .here((users) => {
        onlineUsers = users;
    })
    .joining((user) => {
        onlineUsers.push(user);
    })
    .leaving((user) => {
        onlineUsers = onlineUsers.filter(u => u.user_id !== user.user_id);
});