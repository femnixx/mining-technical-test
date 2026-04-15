<div class="mt-4">
    <InputLabel for="role" value="Register As" />
    <select 
        id="role" 
        v-model="form.role" 
        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
        required
    >
        <option value="admin">Admin (Pool Manager)</option>
        <option value="approver">Manager (Pihak Menyetujui)</option>
    </select>
    <InputError class="mt-2" :message="form.errors.role" />
</div>