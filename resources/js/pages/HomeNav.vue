<template>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <!-- Left: Logo -->
            <router-link class="navbar-brand fw-bold" to="/">
                <!-- <h1 class="mb-0">Od<span class="earn_text">Share</span></h1> -->

                <img src="/assets/images/videogen2.png" class="w-100" style="height: 50px;" alt=""> </router-link>

            <!-- Toggler Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collapsible content -->
            <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
                <!-- <ul class="navbar-nav d-flex align-items-lg-center mb-2 mb-lg-0 custom-nav-text">
                    <li class="nav-item mx-lg-1">
                        <router-link class="nav-link" to="/services" active-class="active-link">Services</router-link>
                    </li>
                    <li class="nav-item mx-lg-1">
                        <router-link class="nav-link" to="/about_us" active-class="active-link">About</router-link>
                    </li>
                    <li class="nav-item mx-lg-1">
                        <router-link class="nav-link" to="/contact" active-class="active-link">Contact Us</router-link>
                    </li>
                </ul> -->

                <!-- Auth Buttons / Profile -->
                <div class="d-flex align-items-center mt-2 mt-lg-0 ms-lg-3">
                    <div v-if="isLoggedIn">
                        <router-link to="/Dashboard" class="text-decoration-none">
                            <div class="d-flex align-items-center" style="cursor: pointer;">
                                <img src="/assets/images/usericon.png" alt="Profile" class="img-fluid rounded-circle"
                                    width="40" />
                                <h4 class="earn_text ms-2 mb-0">{{ username }}</h4>
                            </div>
                        </router-link>
                    </div>
                    <div v-else>
                        <router-link to="/odlogin">
                            <button class="btn btn-outline-success mx-2 px-3" type="button">Login</button>
                        </router-link>
                        <!-- <router-link to="/odsignup">
                            <button class="btn btn-outline-success" type="button">Sign up</button>
                        </router-link> -->
                    </div>
                </div>
            </div>
        </div>
    </nav>

</template>

<script>
import { toast } from 'vue3-toastify';

export default {
    data() {
        return {
            isLoggedIn: false,
            username: "",
        };
    },
    created() {
        const authToken = localStorage.getItem("authToken");
        const userInfo = localStorage.getItem("userInfo");

        if (authToken && userInfo) {
            try {
                const user = JSON.parse(userInfo);
                this.isLoggedIn = true;
                this.username = user.name; // Extract the name
            } catch (error) {
                console.error("Error parsing userInfo:", error);
                toast.error('Please login again!!')
                this.logout(); // Clear invalid data
            }
        }
    },
    methods: {
        logout() {
            localStorage.removeItem("authToken");
            localStorage.removeItem("userInfo");
            this.isLoggedIn = false;
            this.username = "";
            toast.error('Please login again!!')
            this.$router.push("/odlogin");
        },
    },
};
</script>

<style scoped>
.earn_text {
    color: #0DA600;
}

.active-link {
    color: #0DA600 !important;
    font-weight: 600;

}
</style>
