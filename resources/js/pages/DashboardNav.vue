<template>
    <div class="container mt-2">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid d-flex flex-column flex-lg-row align-items-lg-center">
                <router-link class="navbar-brand fw-bold" to="/">
                    <img src="assets/images/videogen2.png" class="w-100" style="height: 60px;" alt="">
                </router-link>
                <!-- <div class="search-container">
                    <div class="input-group search-bar my-2 my-lg-0">
                        <span class="input-group-text bg-light border-0 ps-3 fs-5"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control form-control-lg bg-light border-0 rounded"
                            placeholder="Search">
                    </div>
                </div> -->
                <div class="profile-container ms-lg-auto d-flex align-items-center">
                    <!-- <router-link class="navbar-brand fw-bold me-auto" to="/">
                    <span class=""></span> <span class="custome_green">Home</span>
                </router-link> -->
                    <div class="dropdown">
                        <div class="d-flex align-items-center" id="profileDropdown" data-bs-toggle="dropdown"
                            aria-expanded="false" style="cursor: pointer;">
                            <img src="assets/images/usericon.png" alt="Profile" class="img-fluid rounded-circle"
                                width="40" />
                            <h4 class="custom_green ms-2 mb-0">{{ username }}</h4>
                        </div>

                        <ul class="dropdown-menu" aria-labelledby="profileDropdown">

                        
                            <li>
                                <button @click="logout" class="dropdown-item text-danger">
                                    Sign Out
                                </button>
                            </li>

                        </ul>
                    </div>

                </div>
            </div>
        </nav>
    </div>


</template>

<script>
import { toast } from 'vue3-toastify';

export default {
    data() {
        return {
            username: '',
        };
    },

    methods: {
        logout() {
            localStorage.removeItem('authToken');
            localStorage.removeItem('userInfo');

            toast.success('Logout Successfully!!', {
                autoClose: 2000
            });

            setTimeout(() => {
                this.$router.push('/');
            }, 2000);
        }
    },

    mounted() {
        const userInfo = localStorage.getItem('userInfo');
        if (userInfo) {
            try {
                const user = JSON.parse(userInfo);
                this.username = user.name || 'Dear';
            } catch (error) {
                console.error('Error parsing userInfo:', error);
                this.username = 'Dear';
            }
        } else {
            this.username = 'Guest';
        }
    },
};
</script>

<style scoped>
.dropdown-item:active {
    background-color: #198754 !important;
    /* Bootstrap 'bg-success' */
    color: #fff !important;
}


.custom-nav-text .nav-link {
    font-size: 20px;
    font-weight: 400;
}

.navbar-brand {
    display: flex;
    align-items: center;
    font-size: 32px;
    color: rgba(51, 51, 51, 1);
}

.navbar-brand .ods {
    color: purple;
    /* Update this to match your design */
}

.navbar-brand .custome_green {
    font-size: 26px;
    color: rgba(13, 166, 0, 1);
}

.icon-and-image {
    display: flex;
    align-items: center;
    gap: 20px;
}

.fa-bell {
    font-size: 2rem;
}

.custom_success {
    color: rgba(13, 166, 0, 1);
}

.search-bar {
    max-width: 500px;
}

.search-bar input {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 0.375rem 0.75rem;
    width: 100%;
}

.search-bar i {
    color: #6c757d;
}

.search-container {
    flex-grow: 1;
    display: flex;
    justify-content: center;
}

.profile-container {
    display: flex;
    align-items: center;
    gap: 10px;
}

@media (max-width: 992px) {
    .search-container {
        width: 100%;
        margin-top: 10px;
    }
}
</style>
