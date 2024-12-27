<div>
    <flux:heading size="xl">RPCP</flux:heading>
    <flux:subheading>Roles and Permission Page</flux:subheading>
    <div class=" p-4 my-8 rounded max-w-[1400px] m-auto dark:text-slate-200 ">
        <div class="<x-css.panel />">
            <div class="lg:grid sm:grid-cols-4 ">
                <!-- Roles -->
                <div class="px-6 pt-4 mt-4 ">
                    <form wire:submit.prevent="add_role">
                        <div class="mb-6 text-xl font-bold dark:text-white">Roles</div>
                        <div>
                            @error('role_name')
                                <span class="text-xs text-red-500 error">{{ $message }}</span>
                            @enderror
                        </div>

                        <input type=text wire:model="role_name" placeholder="role"
                            class="<x-css.form-input /> border-slate-400 border p-2 mr-2">
                        <button type="submit" {{ $role_name }}
                            class="<x-css.button /> p-2 disabled:bg-white disabled:text-slate-300">Add
                        </button>
                    </form>
                    <input wire:model="roleSearch2" class="<x-css.form-input /> border-slate-400 border p-2 mt-2"
                        placeholder="Search roles">
                    <div>
                        <div class="">
                            <div class="pl-2 grid grid-cols-4 pt-4  my-3 <x-css.table-column-headings />">
                                {{-- <div></div> --}}
                                <div class="col-span-3">Role</div>
                                {{-- <div class="col-span-4">Guard</div> --}}
                                <div></div>
                            </div>

                            @foreach ($roles as $role)
                                <div class="<x-css.table-row /> py-2 pl-2  grid grid-cols-4 transition">
                                    {{-- <div class="">{{ $role->id }}</div> --}}
                                    <div class="col-span-3">{{ $role->name }} </div>
                                    {{-- <div class="col-span-4">{{ $role->guard_name }}</div> --}}
                                    <div x-data="{ roleDialogueOpen: false }" class="flex justify-center">
                                        <span x-on:click="roleDialogueOpen = true"
                                            class="flex justify-center px-2 rounded-full cursor-pointer hover:text-black hover:bg-slate-200 text-slate-400">
                                            x
                                        </span>

                                        <!-- Modal -->
                                        <div x-show="roleDialogueOpen" style="display: none"
                                            x-on:keydown.escape.prevent.stop="roleDialogueOpen = false" role="dialog"
                                            aria-modal="true" x-id="['modal-title']"
                                            :aria-labelledby="$id('modal-title')"
                                            class="fixed inset-0 z-10 overflow-y-auto">
                                            <!-- Overlay -->
                                            <div x-show="roleDialogueOpen" x-transition.opacity
                                                class="fixed inset-0 bg-black bg-opacity-50"></div>

                                            <!-- Panel -->
                                            <div x-show="roleDialogueOpen" x-transition
                                                x-on:click="roleDialogueOpen = false"
                                                class="relative flex items-center justify-center min-h-screen p-4">
                                                <div x-on:click.stop x-trap.noscroll.inert="roleDialogueOpen"
                                                    class="relative w-full max-w-2xl overflow-y-auto rounded <x-css.modal-panel />  p-12 shadow-lg">
                                                    <!-- Title -->
                                                    <h2 class="text-3xl font-bold <x-css.text.normal />"
                                                        :id="$id('modal-title')">
                                                        Confirm</h2>

                                                    <!-- Content -->
                                                    <p class="mt-2 <x-css.text.normal />">Are
                                                        you sure you want to delete role: {{ $role->id }}
                                                        {{ $role->name }}? </p>



                                                    <!-- Buttons -->

                                                    <div class="flex mt-8 space-x-2">
                                                        <button wire:click="delete_role({{ $role->id }})"
                                                            x-on:click="roleDialogueOpen=false"
                                                            class="rounded border border-gray-200 dark:text-slate-700 bg-white px-5 py-2.5">
                                                            Delete
                                                        </button>

                                                        <button type="button" x-on:click="roleDialogueOpen = false"
                                                            class="rounded border border-gray-200 dark:text-slate-700 bg-white px-5 py-2.5">
                                                            Cancel
                                                        </button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Permissions -->
                <div
                    class="px-6 pt-4 mt-4 border-t border-b border-l-0 lg:border-l lg:border-r lg:border-t-0 lg:border-b-0 border-slate-300">
                    <form wire:submit.prevent="add_permission">
                        <div class="mb-6 text-xl font-bold dark:text-white">Permissions</div>
                        <div>
                            @error('permission_name')
                                <span class="text-xs text-red-500 error">{{ $message }}</span>
                            @enderror
                        </div>
                        <input wire:model="permission_name" placeholder="permission"
                            class="<x-css.form-input />  border-slate-400
                            border p-2 mr-2">
                        <button type="submit" class="<x-css.button /> ">Add
                        </button>
                    </form>
                    <input wire:model="permissionSearch2" class="<x-css.form-input /> border-slate-400 border p-2 mt-2"
                        placeholder="Search permissions">
                    <div class="">
                        <div class="pt-4 text-slate-600 dark:text-slate-200">
                            <div class="<x-css.table-column-headings /> pl-2 grid grid-cols-4 pt-4  my-3 ">
                                {{-- <div></div> --}}
                                <div class="col-span-3 dark:text-white">Permission</div>
                                <div></div>
                            </div>
                            @foreach ($permissions as $permission)
                                <div class="<x-css.table-row />  py-2 pl-2  transition grid grid-cols-4">
                                    {{-- <div class="">{{ $permission->id }}</div> --}}
                                    <div class="col-span-3"> {{ $permission->name }}</div>
                                    {{-- <div class="col-span-4">{{ $permission->guard_name }}</div> --}}
                                    <div x-data="{ permissionDialogueOpen: false }" class="flex justify-center">
                                        <span x-on:click="permissionDialogueOpen = true"
                                            class="flex justify-center px-2 rounded-full cursor-pointer hover:text-black hover:bg-slate-200 text-slate-400">
                                            x
                                        </span>

                                        <!-- Modal -->
                                        <div x-show="permissionDialogueOpen" style="display: none"
                                            x-on:keydown.escape.prevent.stop="permissionDialogueOpen = false"
                                            permission="dialog" aria-modal="true" x-id="['modal-title']"
                                            :aria-labelledby="$id('modal-title')"
                                            class="fixed inset-0 z-10 overflow-y-auto">
                                            <!-- Overlay -->
                                            <div x-show="permissionDialogueOpen" x-transition.opacity
                                                class="fixed inset-0 bg-black bg-opacity-50"></div>

                                            <!-- Panel -->
                                            <div x-show="permissionDialogueOpen" x-transition
                                                x-on:click="permissionDialogueOpen = false"
                                                class="relative flex items-center justify-center min-h-screen p-4">
                                                <div x-on:click.stop x-trap.noscroll.inert="permissionDialogueOpen"
                                                    class="relative w-full max-w-2xl overflow-y-auto rounded <x-css.modal-panel />  p-12 shadow-lg">
                                                    <!-- Title -->
                                                    <h2 class="text-3xl font-bold <x-css.text.normal />"
                                                        :id="$id('modal-title')">
                                                        Confirm</h2>

                                                    <!-- Content -->
                                                    <p class="mt-2 <x-css.text.normal />">Are
                                                        you sure you want to delete permission: {{ $permission->id }}
                                                        {{ $permission->name }}?
                                                        <!-- Buttons -->

                                                    <div class="flex mt-8 space-x-2">
                                                        <button wire:click="delete_permission({{ $permission->id }})"
                                                            x-on:click="permissionDialogueOpen=false"
                                                            class="rounded border border-gray-200 dark:text-slate-700 bg-white px-5 py-2.5">
                                                            Delete
                                                        </button>

                                                        <button type="button"
                                                            x-on:click="permissionDialogueOpen = false"
                                                            class="rounded border border-gray-200 dark:text-slate-700 bg-white px-5 py-2.5">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Associations -->
                <div class="flex flex-col col-span-2 pt-4 pl-6 mt-4 ">
                    <div class="w-full ">
                        <form wire:submit.prevent="associate">
                            <div class="mb-6 text-xl font-bold dark:text-white">Associate Roles with Permissions</div>
                            <select
                                class="<x-css.form-input /> w-48 appearance-none   border-slate-400 mr-2 dark:placeholder-red-400 placeholder:text-slate-400"
                                wire:model="assoc_role">
                                <option>Select a role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}
                                    </option>
                                @endforeach
                            </select>

                            <select class="<x-css.form-input /> w-48 appearance-none  border-slate-400 mr-2"
                                wire:model="assoc_permission">
                                <option>Select a permission</option>
                                @foreach ($permissions as $permission)
                                    <option
                                        class="<x-css.form-input />
                                "value="{{ $permission->name }}">
                                        {{ $permission->name }}</option>
                                @endforeach
                            </select>
                            <button class="<x-css.button /> p-2">Associate</button>
                        </form>
                    </div>
                    <div class="flex flex-col gap-2 my-3">


                    </div>
                    <div class="pt-4 text-slate-600 dark:text-slate-200">
                        <div class="<x-css.table-column-headings />pl-2 grid grid-cols-9 pt-4  my-3 ">
                            {{-- <div>ID</div> --}}
                            <div class="col-span-3 dark:text-white">Role</div>
                            {{-- <div>Guard</div> --}}
                            <div class="col-span-5 dark:text-white">Permission</div>
                            {{-- <div>Guard</div> --}}
                            <div></div>
                        </div>
                        <div class="<x-css.table-row /> text-slate-600   dark:text-slate-200">
                            @foreach ($roles as $role)
                                @foreach ($role->getAllPermissions() as $permission)
                                    <div
                                        class="<x-css.table-row /> py-2 pl-2 text-slate-600   grid grid-cols-9 dark:text-slate-200">
                                        {{-- <div>id</div> --}}
                                        <div class="col-span-3">{{ $role->name }}</div>
                                        {{-- <div>{{ $role->guard_name }}</div> --}}
                                        <div class="col-span-5"> {{ $permission->name }}</div>
                                        {{-- <div>{{ $permission->guard_name }}</div> --}}
                                        <div x-data="{ assocDialogueOpen: false }" class="flex justify-center">
                                            <span x-on:click="assocDialogueOpen = true"
                                                class="flex justify-center px-2 rounded-full cursor-pointer hover:text-black hover:bg-slate-200 text-slate-400">
                                                x
                                            </span>

                                            <!-- Modal -->
                                            <div x-show="assocDialogueOpen" style="display: none"
                                                x-on:keydown.escape.prevent.stop="assocDialogueOpen = false"
                                                role="dialog" aria-modal="true" x-id="['modal-title']"
                                                :aria-labelledby="$id('modal-title')"
                                                class="fixed inset-0 z-10 overflow-y-auto">
                                                <!-- Overlay -->
                                                <div x-show="assocDialogueOpen" x-transition.opacity
                                                    class="fixed inset-0 bg-black bg-opacity-50"></div>

                                                <!-- Panel -->
                                                <div x-show="assocDialogueOpen" x-transition
                                                    x-on:click="assocDialogueOpen = false"
                                                    class="relative flex items-center justify-center min-h-screen p-4">
                                                    <div x-on:click.stop x-trap.noscroll.inert="assocDialogueOpen"
                                                        class="relative w-full max-w-2xl overflow-y-auto rounded <x-css.modal-panel />  p-12 shadow-lg">
                                                        <!-- Title -->
                                                        <h2 class="text-3xl font-bold <x-css.text.normal />"
                                                            :id="$id('modal-title')">
                                                            Confirm</h2>

                                                        <!-- Content -->
                                                        <p class="mt-2 <x-css.text.normal />">Are
                                                            you sure you
                                                            want to delete association: {{ $role->name }}
                                                            {{ $permission->name }}?
                                                        </p>



                                                        <!-- Buttons -->

                                                        <div class="flex mt-8 space-x-2">
                                                            <button
                                                                wire:click="dissociate( '{{ $role->name }}' , '{{ $permission->name }}' )"
                                                                x-on:click="assocDialogueOpen=false"
                                                                class="rounded border border-gray-200 dark:text-slate-700 bg-white px-5 py-2.5">
                                                                Delete
                                                            </button>

                                                            <button type="button"
                                                                x-on:click="assocDialogueOpen = false"
                                                                class="rounded border border-gray-200 dark:text-slate-700 bg-white px-5 py-2.5">
                                                                Cancel
                                                            </button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
