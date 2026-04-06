<template>
    <div class="container">
        <homenav-component />
        <div class="row ">
            <div class="col-md-5 text-center mt-5">
                <h3 class="fw-bold">Login to Video<span class="earn_text">Generator </span> Dashboard</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-sm-10 col-md-5 mt-5">
                <form @submit.prevent="loginUser">


                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Enter your email address" class="form-control p-2"
                        v-model="email" />
                    <div class="mt-4">
                        <label for="password">Password</label>
                        <div class="input-wrapper position-relative">
                            <input type="password" id="password" name="password" placeholder="Enter your password"
                                class="form-control p-2 pl-5" v-model="password" />
                        </div>
                        <div class="forgot-password mt-2 text-left">
                            <a href="/forget-password" class="forgot-password-link">Forgot Password?</a>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button class="btn w-100 btn-custom-succes" :disabled="loading" @click="loginUser">
                            <span v-if="loading"><i class="fa fa-spinner fa-spin"></i>Login...</span>
                            <span v-else>Login</span>
                        </button>
                    </div>

                </form>

                <!-- <div class="mt-3 text-center">
                    <div class="d-flex align-items-center">
                        <hr class="flex-grow-1" />
                        <span class="p-3">or</span>
                        <hr class="flex-grow-1" />
                    </div>
                </div> -->
                <!-- signup -->
                <!-- <div class="mt-4">
                    <router-link to="/odsignup">
                        <button class="btn w-100 btn-custom-outline">Signup</button>
                    </router-link>
                </div> -->
                <!-- social logins -->
                <!-- <div class="mt-4 card shadow-md">
                    <button class="btn w-100">
                        <img src="assets/images/googlelogin.svg" class="img_size" alt="Google Logo" />
                        Continue with Google
                    </button>
                </div>
                <div class="mt-4">
                    <button class="btn w-100 btn-custom-twiter">
                        <img src="assets/images/twiterlogin.svg" class="img_size_twiter" alt="Twitter Logo" />
                        Continue with Twitter
                    </button>
                </div> -->
                <div class="mt-2 text-center span_text">
                    <span>By continuing, you agree to our </span>
                    <router-link to="/terms-of-Service" class="text-decoration-none"><span class="span_custom"> Terms
                            and Conditions</span></router-link>
                    <span> and </span>
                    <router-link to="/privacy-policy" class="text-decoration-none"> <span class="span_custom"> Privacy
                            Policy</span></router-link>
                </div>
            </div>
            <div class="col-md-5 col-lg-5 col-sm-10 offset-md-2 mt-2">
                <img src="/assets/images/odlogin.svg" class="w-100 d-none d-sm-inline-block" height="auto" />
            </div>
        </div>
    </div>
    <homefooter-component />

</template>
<script>
import axios from 'axios';
import { toast } from 'vue3-toastify';

export default {
    data() {
        return {
            email: '',
            password: '',
            loading: false,

        };
    },
    methods: {
        async loginUser() {
            try {
                this.loading = true;

                // Validate email and password
                if (!this.email || !this.password) {
                    toast.error('Email and password are required.');
                    this.loading = false; // Ensure loading is reset if validation fails
                    return;
                }

                // Make API call
                const response = await axios.post('/api/login-user', {
                    email: this.email,
                    password: this.password,
                });

                // Handle successful login
                const { token, user } = response.data;
                toast.success('Login successfully!');
                console.log('Token:', token);
                console.log('User:', user);
                localStorage.setItem('authToken', token);
                localStorage.setItem('userInfo', JSON.stringify(user));

                // Redirect user to another page
                setTimeout(() => {
                    this.$router.push('/Dashboard');
                }, 2000);

            } catch (error) {
                if (error.response && error.response.status === 401) {
                    toast.error('Invalid email or password.');
                } else if (error.response && error.response.data && error.response.data.message) {
                    // Display the error message sent from Laravel controller
                    toast.error(error.response.data.message);
                } else {
                    toast.error('An error occurred. Please try again.');
                }
            } finally {
                this.loading = false;
            }

        },
    },
    mounted(){
        console.log('Listening on test-channel...');

window.Echo.channel('test-channel')
    .listen('TestEvent', (e) => {   // ← NO DOT, EXACT NAME
        console.log('Received:', e);
        toast.success(e.message);
    });
    }

};
</script>

<style>
.earn_text {
    color: #0DA600;
}

.forgot-password {
    text-align: right;
}

.forgot-password-link {
    border: none;
    font-size: 0.9rem;
    color: rgba(13, 166, 0, 1);
    text-decoration: none;
}

.btn-custom-succes {
    background-color: rgba(13, 166, 0, 1);
    color: white;
    font-size: 17px;
}

.btn-custom-succes:hover {
    background-color: rgb(15, 182, 0);
    color: white;
    font-size: 17px;
}

.btn-custom-outline {
    color: rgba(13, 166, 0, 1);
    border-color: rgba(13, 166, 0, 1);
}

.btn-custom-outline:hover {
    color: white;
    background-color: rgba(13, 166, 0, 1);
}

.img_size {
    width: 40px;
    height: 25px;
}

.img_size_fb {
    width: 40px;
    height: 25px;
}

.btn-custom-fb {

    border-color: rgba(24, 119, 242, 1);
}

.btn-custom-fb:hover {
    color: white;
    background-color: rgba(24, 119, 242, 1);
}

.img_size_twiter {
    width: 40px;
    height: 25px;
}

.btn-custom-twiter {

    border-color: #5c5a5a;
}

.btn-custom-twiter:hover {
    color: white;
    background-color: #444444;
}

.span_custom {
    color: rgba(13, 166, 0, 1);
}

.span_text {
    font-size: 15px;
}
</style>
