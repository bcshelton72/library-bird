<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { useConfirm } from "primevue/useconfirm";

const props = defineProps({
    available: Boolean,
    bookId: Number,
});

const can = usePage().props.auth.permissions;

const confirm = useConfirm();

const checkoutBook = (bookId) => {
    confirm.require({
        message: 'Are you sure you want to check out this book?',
        header: 'Confirmation',
        rejectProps: {
            label: 'No thanks',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Yes it looks great!'
        },
        accept: () => {
            router.put(route("book.checkout", bookId));
        },
        reject: () => {
            //
        }
    });
};
</script>

<template>
    <Button v-if="can.checkout_book && available"
        @click="checkoutBook(bookId)"  label="Check Out" size="small" type="button" />
</template>
