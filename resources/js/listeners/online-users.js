import '../websocket';

let onlineUsers = Alpine.reactive([]);

Echo.join('online-users')
    .here((users) => {
        onlineUsers.splice(0, onlineUsers.length, ...users);
    })
    .joining((user) => {
        onlineUsers.push(user);
    })
    .leaving((user) => {
        const index = onlineUsers.findIndex(u => u.user_id === user.user_id);
        if (index > -1) {
            onlineUsers.splice(index, 1);
        }
    });

Alpine.store('onlineUsers', {
    list: onlineUsers,
    currentPage: 1,
    perPage: 5,

    paginatedUsers() {
        const start = (this.currentPage - 1) * this.perPage;
        const end = start + this.perPage;
        return this.list.slice(start, end);
    },

    changePage(page) {
        const totalPages = Math.ceil(this.list.length / this.perPage);
        if (page >= 1 && page <= totalPages) {
            this.currentPage = page;
        }
    },

    getTotalPages() {
        return Math.ceil(this.list.length / this.perPage);
    },

    updateList() {
        this.list = [...this.list];
    }
});