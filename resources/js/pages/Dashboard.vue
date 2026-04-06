<template>
    <odearnnav-component />
    <hr />

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold mb-0 text-dark">🎬 Video Generator</h4>
                        </div>

                        <!-- Prompt Input -->
                        <div class="mb-4">
                            <label for="prompt" class="form-label fw-semibold">Describe your video idea</label>
                            <textarea id="prompt" v-model="prompt" class="form-control shadow-sm" rows="4"
                                placeholder="e.g. A cinematic view of a city at sunrise with flying cars..."></textarea>
                        </div>

                        <!-- Optional Image Upload -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Optional Image (for better results)</label>
                            <input type="file" accept="image/*" @change="handleImageUpload" class="form-control shadow-sm" />
                            <div v-if="previewImage" class="mt-3 text-center">
                                <img :src="previewImage" class="rounded shadow-sm" style="max-width: 250px;" />
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="row align-items-end mb-4">
                            <div class="col-md-6 col-sm-12">
                                <label class="form-label fw-semibold">Duration</label>
                                <select v-model="duration" class="form-select shadow-sm">
                                    <option value="4">4 seconds</option>
                                    <option value="8">8 seconds</option>
                                    <option value="12">12 seconds</option>
                                    <option value="16">16 seconds</option>
                                    <option value="20">20 seconds</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label class="form-label fw-semibold">Resolution</label>
                                <select v-model="resolution" class="form-select shadow-sm">
                                    <option value="720x1280">720×1280 (Portrait)</option>
                                    <option value="1280x720">1280×720 (Landscape)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Generate Button -->
                        <div class="row">
                            <div class="col-md-12 col-sm-12 d-flex justify-content-start mt-2 mt-md-0">
                                <button class="btn custom_btn px-4 w-100" :disabled="loading || !prompt"
                                    @click="generateVideo">
                                    <span v-if="loading">
                                        <i class="fa fa-spinner fa-spin me-2"></i>Generating...
                                    </span>
                                    <span v-else>
                                        <i class="fa fa-magic me-2"></i>Generate Video
                                    </span>
                                </button>
                            </div>
                        </div>

                        <hr class="my-5" />

                        <!-- Generated Videos -->
                        <div v-if="videos.length">
                            <h5 class="fw-bold mb-3 text-dark">Generated Videos</h5>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Preview</th>
                                            <th>Prompt</th>
                                            <th>Duration</th>
                                            <th>Resolution</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="video in videos" :key="video.id">
                                            <td>
                                                <template v-if="video.video_url && video.status === 'completed'">
                                                    <video :src="`/storage/sora_videos/${video.video_url}`" style="min-width: 200px;max-width: 200px; max-height: 200px;
                                                        min-height: 200px; cursor: pointer;"
                                                        @click="openVideoModal(video.video_url)">
                                                    </video>
                                                </template>
                                                <template
                                                    v-else-if="video.status === 'processing' || video.status === 'queued'">
                                                    <div class="d-flex align-items-center">
                                                        <span class="text-warning me-2">{{ video.progress ?? 0
                                                            }}%</span>
                                                        <i class="fa fa-spinner fa-spin"></i>
                                                    </div>
                                                    <div class="progress mt-1" style="height: 6px; width: 100%;">
                                                        <div class="progress-bar bg-warning" role="progressbar"
                                                            :style="{ width: (video.progress ?? 0) + '%' }"></div>
                                                    </div>
                                                </template>
                                                <template v-else-if="video.status === 'failed'">
                                                    <span class="text-danger">Failed to generate</span>
                                                    <i class="fa fa-exclamation-triangle ms-2"></i>
                                                </template>
                                                <template v-else>
                                                    <span class="text-muted">Pending…</span>
                                                </template>
                                            </td>
                                            <td style="max-width: 280px;">
                                                <small class="text-muted">{{ video.prompt }}</small>
                                            </td>
                                            <td>{{ video.duration }}s</td>
                                            <td>{{ video.resolution }}</td>
                                            <td>
                                                <span :class="statusClass(video.status)">
                                                    {{ mapStatus(video.status) }}
                                                </span>
                                            </td>
                                            <td>{{ video.date }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div v-else class="text-center text-muted mt-4">
                            <i class="fa fa-film mb-2" style="font-size: 2rem;"></i>
                            <p>No videos generated yet — start by entering a prompt!</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
            <div class="modal-content" style="height: 500px;">
                <div class="modal-header">
                    <h5 class="modal-title">Video Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center"
                    style="height: calc(100% - 56px);">
                    <video v-if="currentVideoUrl" :src="`/storage/sora_videos/${currentVideoUrl}`" controls autoplay
                        style="max-width: 100%; max-height: 100%;">
                    </video>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { toast } from "vue3-toastify";

export default {
    data() {
        return {
            username: "",
            prompt: "",
            duration: "8",
            resolution: "1280x720",
            loading: false,
            videos: [],
            currentVideoUrl: null,
            authToken: null,
            userId: null,
            imageFile: null,
            previewImage: null,
        };
    },

    mounted() {
        console.log('🔊 Setting up Echo listener for test-channel');
    
    window.Echo.channel('test-channel')
        .listen('TestEvent', (e) => {  // Removed the dot prefix
            console.log('📩 TestEvent received:', e);
            
            // Show toast with message from backend
            if (this.$toast) {
                this.$toast.success(e.message || 'Received a TestEvent!');
            } else {
                console.log('TestEvent message:', e.message);
            }
        });
        
    // Add connection status monitoring
    window.Echo.connector.socket.on('connect', () => {
        console.log('✅ Connected to Echo server');
    });
    
    window.Echo.connector.socket.on('disconnect', () => {
        console.log('❌ Disconnected from Echo server');
    });

        this.authToken = localStorage.getItem("authToken");

        const userInfo = localStorage.getItem("userInfo");
        if (userInfo) {
            const user = JSON.parse(userInfo);
            this.username = user.name || "Dear";
            this.userId = user.id;
        }

        this.fetchVideos();

        if (this.userId && window.Echo) {
            window.Echo.private(`sora-videos.${this.userId}`)
                .listen('SoraVideoUpdated', (data) => {
                    console.log("Realtime event received:", data);
                    const idx = this.videos.findIndex(v => v.id === data.id);
                    if (idx !== -1) {
                        this.videos[idx] = { ...this.videos[idx], ...data, date: new Date().toLocaleString() };
                    } else {
                        this.videos.unshift({
                            ...data,
                            date: new Date().toLocaleString(),
                            progress: data.progress ?? 0,
                        });
                    }
                });
        }

        const videoModalEl = document.getElementById('videoModal');
        videoModalEl.addEventListener('hidden.bs.modal', () => {
            const videoEl = videoModalEl.querySelector('video');
            if (videoEl) videoEl.pause();
        });
    },

    methods: {

        
        handleImageUpload(e) {
            const file = e.target.files[0];
            if (file) {
                this.imageFile = file;
                this.previewImage = URL.createObjectURL(file);
            }
        },

        getAxiosConfig() {
            return { headers: { Authorization: `Bearer ${this.authToken}`, "Content-Type": "multipart/form-data" } };
        },

        async fetchVideos() {
            try {
                const res = await axios.get("/api/sora/videos", this.getAxiosConfig());
                this.videos = res.data.videos.map(v => ({
                    ...v,
                    date: new Date(v.created_at).toLocaleString(),
                    progress: v.progress || 0,
                }));
            } catch (error) {
                console.error(error);
                toast.error("Failed to fetch videos");
            }
        },

        async generateVideo() {
    if (!this.prompt) return;
    this.loading = true;

    try {
        const formData = new FormData();
        formData.append("prompt", this.prompt);
        formData.append("duration", this.duration);
        formData.append("resolution", this.resolution);
        if (this.imageFile) formData.append("image", this.imageFile);

        const res = await axios.post("/api/sora/generate", formData, {
            headers: {
                Authorization: `Bearer ${this.authToken}`,
                "Content-Type": "multipart/form-data",
            },
        });

        const newVideo = {
            id: Date.now(),
            prompt: this.prompt,
            duration: this.duration,
            resolution: this.resolution,
            status: "completed",
            video_url: res.data.video_url ?? null,
            progress: 100,
            date: new Date().toLocaleString(),
        };

        this.videos.unshift(newVideo);
        this.prompt = "";
        this.imageFile = null;
        this.previewImage = null;

        toast.success("🎬 Video generated successfully!");
    } catch (error) {
        console.error(error);
        toast.error("Failed to generate video");
    } finally {
        this.loading = false;
    }
},


        openVideoModal(videoName) {
            this.currentVideoUrl = videoName;
            const videoModalEl = document.getElementById('videoModal');
            const modal = new bootstrap.Modal(videoModalEl);
            modal.show();
        },

        statusClass(status) {
            switch (status) {
                case "queued": return "badge bg-secondary";
                case "processing": return "badge bg-warning text-dark";
                case "completed": return "badge bg-success";
                case "failed": return "badge bg-danger";
                default: return "badge bg-secondary";
            }
        },

        mapStatus(status) {
            if (status === "completed") return "Complete";
            return status.charAt(0).toUpperCase() + status.slice(1);
        },
    },
};
</script>

<style scoped>
.custom_green {
    color: #0da600;
}

.custom_btn {
    background-color: #0da600;
    color: white;
    font-weight: 500;
    transition: 0.3s ease;
    border-radius: 8px;
}

.custom_btn:hover {
    background-color: #0b8800;
    transform: translateY(-1px);
}

textarea:focus,
select:focus,
input:focus {
    border-color: #0da600 !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 166, 0, 0.15) !important;
}
</style>
