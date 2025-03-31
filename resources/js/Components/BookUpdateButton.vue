<script setup>
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    bookId: Number,
});

const can = usePage().props.auth.permissions;
</script>

<template>
    <Link v-if="can.update_book" :href="route('book.edit', bookId)">
        <Button label="Update" size="small" severity="primary" />
    </Link>
</template>
