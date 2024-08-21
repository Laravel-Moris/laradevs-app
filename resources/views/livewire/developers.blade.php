<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Http;

new class extends Component {
    /**
     * Developers list.
     */
    public array $developers = [];

    /**
     * Show create form.
     */
    public bool $showCreate = false;

    /**
     * Show edit form.
     */
    public bool $showEdit = false;

    /**
     * Show delete form.
     */
    public bool $showDelete = false;

    /**
     * Api Token.
     */
    public string $token = '1|C5T50L7X74rUqP8ZZsqyH2V2JyeOzQrBhxuX0Yb61a3fda90';

    /**
     * Create form data.
     */
    public array $createFormData = [
        'name' => '',
        'bio' => '',
    ];

    /**
     * Edit form data.
     */
    public array $editFormData = [
        'id' => null,
        'name' => '',
        'bio' => '',
    ];

    /**
     * Developer id.
     */
    public ?int $developerId = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->index();
    }

    /**
     * List all developers.
     */
    public function index(): void
    {
        $this->developers = Http::withHeader('accept', 'application/json')
            ->withToken($this->token)
            ->get('https://laradevs.test/api/v1/developers')
            ->json();
    }

    /**
     * Show create form.
     */
    public function create(): void
    {
        $this->showCreate = true;
    }

    /**
     * Store the developer.
     */
    public function store(): void
    {
        $response = Http::withHeader('accept', 'application/json')
            ->withToken($this->token)
            ->post('https://laradevs.test/api/v1/developers', [
                'name' => $this->createFormData['name'],
                'bio' => $this->createFormData['bio'],
            ]);

        if ($response->successful()) {
            $this->showCreate = false;
            $this->index();
        }

        $this->createFormData = [
            'name' => '',
            'bio' => '',
        ];
    }

    /**
     * Show edit form.
     */
    public function edit(int $id): void
    {
        $this->editFormData = collect($this->developers)->firstWhere('id', $id);
        $this->showEdit = true;
    }

    /**
     * Update the developer.
     */
    public function update(): void
    {
        $response = Http::withHeader('accept', 'application/json')
            ->withToken($this->token)
            ->put("https://laradevs.test/api/v1/developers/{$this->editFormData['id']}", [
                'name' => $this->editFormData['name'],
                'bio' => $this->editFormData['bio'],
            ]);

        if ($response->successful()) {
            $this->showEdit = false;
            $this->index();
        }

        $this->editFormData = [
            'id' => null,
            'name' => '',
            'bio' => '',
        ];
    }

    /**
     * Delete the developer.
     */
    public function delete(int $id): void
    {
        $this->showDelete = true;
        $this->developerId = $id;
    }

    /**
     * Destroy the developer.
     */
    public function destroy(): void
    {
        $response = Http::withHeader('accept', 'application/json')
            ->withToken($this->token)
            ->delete("https://laradevs.test/api/v1/developers/$this->developerId");

        if ($response->successful()) {
            $this->showDelete = false;
            $this->index();
        }

        $this->developerId = null;
    }
}; ?>

<div>
  <div class="bg-gray-900">
    <div class="mx-auto max-w-7xl">
      <div class="bg-gray-900 py-10">
        <div class="px-4 sm:px-6 lg:px-8">
          <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
              <h1 class="text-base font-semibold leading-6 text-white">Developers</h1>
              <p class="mt-2 text-sm text-gray-300">A list of all the developers available.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
              <button
                type="button"
                class="block rounded-md bg-indigo-500 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                wire:click.prevent="create"
              >
                Add developer
              </button>
            </div>
          </div>
          <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-700">
                  <thead>
                  <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-0">Name</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Bio</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Created</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                      <span class="sr-only">Edit</span>
                    </th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                      <span class="sr-only">Delete</span>
                    </th>
                  </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-800">
                  @foreach($developers as $developer)
                    <tr>
                      <td
                        class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-0">{{ $developer['name'] }}</td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                        {{ $developer['bio'] }}
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                        {{ \Illuminate\Support\Carbon::parse($developer['created_at'])->diffForHumans() }}
                      </td>
                      <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                        <a href="#" wire:click.prevent="edit({{ $developer['id'] }})" class="text-indigo-400 hover:text-indigo-300">Edit<span
                            class="sr-only">, {{ $developer['name'] }}</span></a>
                      </td>
                      <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                        <a href="#" wire:click.prevent="delete({{ $developer['id'] }})" class="text-red-400 hover:text-red-300">Delete<span
                            class="sr-only">, {{ $developer['name'] }}</span></a>
                      </td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div
    x-data="{ isOpen: @entangle('showCreate').live }"
    x-show="isOpen"
    class="relative z-10"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
  >
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
      <div
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
      >

        <div
          class="relative transform overflow-hidden rounded-lg bg-gray-900 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6"
        >
          <form wire:submit.prevent="store">
            <div class="space-y-12">
              <div class="border-b border-white/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-white">Add new Developer</h2>
                <p class="mt-1 text-sm leading-6 text-gray-400">Create a new developer profile.</p>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="sm:col-span-4">
                    <label for="name" class="block text-sm font-medium leading-6 text-white">Name</label>
                    <div class="mt-2">
                      <div class="flex rounded-md bg-white/5 ring-1 ring-inset ring-white/10 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
                        <input
                          type="text"
                          name="name"
                          id="name"
                          autocomplete="name"
                          class="flex-1 border-0 bg-transparent py-1.5 pl-1 text-white focus:ring-0 sm:text-sm sm:leading-6"
                          placeholder="Taylor Otwell"
                          wire:model="createFormData.name"
                        >
                      </div>
                    </div>
                  </div>

                  <div class="col-span-full">
                    <label for="bio" class="block text-sm font-medium leading-6 text-white">Bio</label>
                    <div class="mt-2">
                      <textarea
                        id="bio"
                        name="bio"
                        rows="3"
                        wire:model="createFormData.bio"
                        class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"></textarea>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-gray-400">Write a few sentences about the developer.</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
              <button type="button" x-on:click="isOpen = false" class="text-sm font-semibold leading-6 text-white">Cancel</button>
              <button type="submit" class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div
    x-data="{ isOpen: @entangle('showEdit').live }"
    x-show="isOpen"
    class="relative z-10"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
  >
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
      <div
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
      >

        <div
          class="relative transform overflow-hidden rounded-lg bg-gray-900 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6"
        >
          <form wire:submit.prevent="update">
            <div class="space-y-12">
              <div class="border-b border-white/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-white">Edit Developer info</h2>
                <p class="mt-1 text-sm leading-6 text-gray-400">Edit developer profile.</p>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="sm:col-span-4">
                    <label for="name" class="block text-sm font-medium leading-6 text-white">Name</label>
                    <div class="mt-2">
                      <div class="flex rounded-md bg-white/5 ring-1 ring-inset ring-white/10 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
                        <input
                          type="text"
                          name="name"
                          id="name"
                          autocomplete="name"
                          class="flex-1 border-0 bg-transparent py-1.5 pl-1 text-white focus:ring-0 sm:text-sm sm:leading-6"
                          placeholder="Taylor Otwell"
                          wire:model="editFormData.name"
                        >
                      </div>
                    </div>
                  </div>

                  <div class="col-span-full">
                    <label for="bio" class="block text-sm font-medium leading-6 text-white">Bio</label>
                    <div class="mt-2">
                      <textarea
                        id="bio"
                        name="bio"
                        rows="3"
                        wire:model="editFormData.bio"
                        class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"></textarea>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-gray-400">Write a few sentences about the developer.</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
              <button type="button" x-on:click="isOpen = false" class="text-sm font-semibold leading-6 text-white">Cancel</button>
              <button type="submit" class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div
    x-data="{ isOpen: @entangle('showDelete').live }"
    x-cloak
    x-show="isOpen"
    class="relative z-10"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
  >
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
      <div
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
      >
        <div class="relative transform overflow-hidden rounded-lg bg-gray-900 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
          <div class="sm:flex sm:items-start">
            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
              <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
              <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Delete this developer</h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">Are you sure you want to delete this developer account? All of your data will be permanently removed from our servers forever. This action cannot be undone.</p>
              </div>
            </div>
          </div>
          <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
              wire:click.prevent="destroy"
            >
              Delete
            </button>
            <button
              type="button"
              class="text-sm font-semibold leading-6 text-white"
              x-on:click.prevent="isOpen = false"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
