<script setup>
import { ref } from 'vue';
import { usePage } from "@inertiajs/vue3";
import { FilterMatchMode } from '@primevue/core/api';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BookCatalog from '@/Components/BookCatalog.vue';
import BookReturnButton from '@/Components/BookReturnButton.vue';

const props = defineProps({
    books: Object,
    overdue_books: Object,
});

const can = usePage().props.auth.permissions;

const overdue_filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    title: { value: null, matchMode: FilterMatchMode.CONTAINS },
    'author.first_name': { value: null, matchMode: FilterMatchMode.CONTAINS },
    'author.last_name': { value: null, matchMode: FilterMatchMode.CONTAINS },
});

</script>
<template>
    <Head title="Manage Books" />
    <AuthenticatedLayout>
        <template #header>
            <ConfirmDialog></ConfirmDialog>
            <div class="flex justify-between">
                <h2
                    class="flex text-xl font-semibold leading-tight text-gray-800"
                >
                    Welcome {{ $page.props.auth.user.name }}!
                </h2>
                <div v-if="$page.props.flash.success" class="alert alert-success">
                    <Message severity="success">{{ $page.props.flash.success }}</Message>
                </div>
                <div v-if="$page.props.flash.error" class="alert alert-danger">
                    <Message severity="error">{{ $page.props.flash.error }}</Message>
                </div>

                <Link v-if="can.create_book" :href="route('book.edit')">
                    <Button label="Add Book" size="small" />
                </Link>
            </div>
        </template>
        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mt-8">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-xl font-semibold">Checked Out Books</h2>
                        <BookCatalog :books="overdue_books" :checkedOut=true />
                    </div>
                </div>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mt-12">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-xl font-semibold">Manage Book Catalog</h2>
                        <BookCatalog :books="books" />
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
