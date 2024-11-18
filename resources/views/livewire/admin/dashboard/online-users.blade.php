<div class="col-5 d-flex">
    <div x-data="{ 
            online: $store.onlineUsers,
            authUserId: {{ auth()->user()->user_id }} 
        }" class="card">
        <div class="card-header w-100 px-3">
            <header class="p-1 fs-4 fw-bold" role="heading" aria-level="2">
                <span class="fs-4 fw-bold text-primary pe-2" x-text="online.list.length"></span>
                {{ __('Online Users') }}
            </header>
        </div>

        <div class="card-body px-3">
            <ul class="list-unstyled">
                <template x-if="online.paginatedUsers().length > 0">
                    <template x-for="user in online.paginatedUsers()" :key="user . user_id">
                        <li class="d-flex align-items-center mb-2 p-2 rounded-3 bg-body-secondary">
                            <x-online-status status="online">
                                <img :src="user . photo" alt="User Photo" class="img-fluid rounded-circle border"
                                    width="40" height="40">
                            </x-online-status>
                            <div class="ms-3">
                                <div class="fw-bold text-uppercase">
                                    <span x-text="user.fullName"></span>
                                    <template x-if="user.user_id === authUserId">
                                        <span class="fw-medium text-capitalize">{{ __('(You)') }}</span>
                                    </template>
                                </div>
                                <div class="text-muted" x-text="user.email"></div>
                            </div>
                        </li>
                    </template>
                </template>

                <div class="d-flex flex-column align-items-center justify-content-center" style="height: 100%;">
                    <template x-if="online.paginatedUsers().length === 0">
                        <div class="text-center">
                            <img class="img-size-40 img-responsive"
                                src="{{ Vite::asset('resources/images/illus/dashboard/time-state.webp') }}" alt="">
                            <div class="text-muted"> {{ __('No online users') }}</div>
                        </div>
                    </template>
                </div>

            </ul>
        </div>

        <div class="card-footer">
            <button @click="online.changePage(online.currentPage - 1)" :disabled="online . currentPage === 1"
                class="btn btn-sm border-0">
                <i data-lucide="chevron-left"></i>
            </button>

            <span>
                <span x-text="online.currentPage"></span>
                {{ __('of') }}
                <span x-text="online.getTotalPages()"></span>
            </span>

            <button @click="online.changePage(online.currentPage + 1)" :disabled="online . currentPage === online . getTotalPages()" class="btn btn-sm border-0">
                <i data-lucide="chevron-right"></i>
            </button>
        </div>
    </div>
</div>