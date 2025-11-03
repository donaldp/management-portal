import { onMounted } from "vue";

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Multiselect from 'vue-multiselect'
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    person: {
        type: Object,
        required: true,
        default: () => ({})
    },
    languages: {
        type: Array,
        required: true,
    },
    interests: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    first_name: props.person.first_name || '',
    last_name: props.person.last_name || '',
    id_number: props.person.id_number || '',
    mobile_number: props.person.mobile_number || '',
    email_address: props.person.email_address || '',
    date_of_birth: props.person.date_of_birth || '',
    language_id: props.person.language_id || null,
    interests: props.person.interests || [],
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                People / Update
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <section class="max-w-xl">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                Upate Person
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                Update the details of this person below.
                            </p>
                        </header>

                        <form
                            @submit.prevent="form.patch(route('people.update', person.id))"
                            class="mt-6 space-y-6"
                        >
                            <div>
                                <InputLabel for="first_name" value="First Name" />

                                <TextInput
                                    id="first_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.first_name"
                                    required
                                    :autofocus="form.errors.first_name"
                                    autocomplete="first_name"
                                />

                                <InputError class="mt-2" :message="form.errors.first_name" />
                            </div>

                            <div>
                                <InputLabel for="last_name" value="Last Name" />

                                <TextInput
                                    id="last_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.last_name"
                                    required
                                    :autofocus="form.errors.last_name"
                                    autocomplete="last_name"
                                />

                                <InputError class="mt-2" :message="form.errors.last_name" />
                            </div>

                            <div>
                                <InputLabel for="id_number" value="ID Number" />

                                <TextInput
                                    id="id_number"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.id_number"
                                    required
                                    :autofocus="form.errors.id_number"
                                    autocomplete="id_number"
                                />

                                <InputError class="mt-2" :message="form.errors.id_number" />
                            </div>

                            <div>
                                <InputLabel for="mobile_number" value="Mobile Number" />

                                <TextInput
                                    id="mobile_number"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.mobile_number"
                                    required
                                    :autofocus="form.errors.mobile_number"
                                    autocomplete="mobile_number"
                                />

                                <InputError class="mt-2" :message="form.errors.mobile_number" />
                            </div>

                            <div>
                                <InputLabel for="email_address" value="Email Address" />

                                <TextInput
                                    id="email_address"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email_address"
                                    required
                                    :autofocus="form.errors.email_address"
                                    autocomplete="email_address"
                                />

                                <InputError class="mt-2" :message="form.errors.email_address" />
                            </div>

                            <div>
                                <InputLabel for="date_of_birth" value="Date of Birth" />

                                <TextInput
                                    id="date_of_birth"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="form.date_of_birth"
                                    required
                                    :autofocus="form.errors.date_of_birth"
                                    autocomplete="date_of_birth"
                                />

                                <InputError class="mt-2" :message="form.errors.date_of_birth" />
                            </div>

                            <div>
                                <InputLabel for="language_id" value="Language" />

                                <select
                                    id="language_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    v-model="form.language_id"
                                    :autofocus="form.errors.language_id"
                                    required
                                >
                                    <option value="" disabled>Select a language</option>
                                    <option v-for="language in $props.languages" :key="language.id" :value="language.id">
                                        {{ language.name }}
                                    </option>
                                </select>

                                <InputError class="mt-2" :message="form.errors.language_id" />
                            </div>

                            <div>
                                <InputLabel for="interests" value="Interests" />

                                <Multiselect
                                    id="interests"
                                    v-model="form.interests"
                                    :options="$props.interests"
                                    :multiple="true"
                                    :close-on-select="false"
                                    :clear-on-select="false"
                                    :preserve-search="true"
                                    placeholder="Select interests"
                                    label="name"
                                    track-by="id"
                                    required
                                />

                                <InputError class="mt-2" :message="form.errors.interests" />
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing">Update</PrimaryButton>

                                <Transition
                                    enter-active-class="transition ease-in-out"
                                    enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out"
                                    leave-to-class="opacity-0"
                                >
                                    <p
                                        v-if="form.recentlySuccessful"
                                        class="text-sm text-gray-600"
                                    >
                                        Updated.
                                    </p>
                                </Transition>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
