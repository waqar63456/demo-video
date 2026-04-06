<template>
    <div>
        <router-view></router-view>
    </div>
</template>

<script setup>
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import { watch } from 'vue'

const route = useRoute()

watch(
    () => route.meta,
    (meta) => {
        useHead({
            title: meta.title || 'MyApp',
            meta: [
                { name: 'description', content: meta.description || '' },

                // ✅ Open Graph
                { property: 'og:title', content: meta.ogTitle || meta.title },
                { property: 'og:description', content: meta.ogDescription || meta.description },

            ],
            link: [
                // ✅ Canonical link
                { rel: 'canonical', href: meta.canonical || window.location.href }
            ]
        })
    },
    { immediate: true }
)
</script>
