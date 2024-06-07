<div>
    <x-tab wire:model.live="tab">
        <x-tab.items :tab="__('authmodule::account.tabs.overview')">
            <x-slot:left>
                <i class="icon-home"></i>
            </x-slot:left>
        </x-tab.items>
        <x-tab.items :tab="__('authmodule::account.tabs.sessions')">
            <x-slot:left>
                <i class="icon-monitor-dot"></i>
            </x-slot:left>
        </x-tab.items>

        <x-view-integration name="authmodule_profile_tabs"/>
    </x-tab>

    @if($tab == __('authmodule::account.tabs.overview'))
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
            <div class="col-span-1 space-y-4">
                <x-card>
                    <div class="flex">
                        <img src="{{ user()->getUser(auth()->user())->getAvatarURL() }}"
                             alt="Avatar" class="h-16 w-16 rounded-3xl mr-4">
                        <div>
                            <p class="font-bold">{{ auth()->user()->username }}</p>
                            <p>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>

                            <x-view-integration name="authmodule_profile_avatar"/>
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <span
                            class="font-bold text-xl">{{ __('authmodule::account.overview.language_and_theme.title') }}</span>
                    </x-slot:header>

                    <x-select.styled :options="[
                            ['label' => __('authmodule::account.overview.language_and_theme.languages.en'), 'value' => 'en'],
                            ['label' => __('authmodule::account.overview.language_and_theme.languages.de'), 'value' => 'de'],
                        ]" select="label:label|value:value" wire:model="language" searchable/>

                    <div class="my-4">
                        <x-select.styled :options="[
                                ['label' => __('authmodule::account.overview.language_and_theme.themes.dark'), 'value' => 'dark'],
                                ['label' => __('authmodule::account.overview.language_and_theme.themes.light'), 'value' => 'light'],
                            ]" select="label:label|value:value" wire:model="theme" searchable/>
                    </div>

                    <x-view-integration name="authmodule_profile_language_and_theme"/>

                    <x-divider/>

                    <x-button class="mt-3" wire:click="updateLanguageAndTheme" loading="updateLanguageAndTheme">
                        {{ __('authmodule::account.overview.language_and_theme.buttons.update_language_and_theme') }}
                    </x-button>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <span
                            class="font-bold text-xl">{{ __('authmodule::account.overview.actions.title') }}</span>
                    </x-slot:header>

                    <x-authmodule::activate-two-factor-modal/>
                    <x-authmodule::show-recovery-codes-modal/>

                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-2">
                        @if(auth()->user()->two_factor_enabled)
                            <x-button wire:click="$toggle('showRecoveryCodesModal')">
                                {{ __('authmodule::account.overview.actions.buttons.show_recovery_codes') }}
                            </x-button>
                            <x-button color="red" wire:click="disableTwoFactor">
                                {{ __('authmodule::account.overview.actions.buttons.disable_two_factor') }}
                            </x-button>
                        @elseif(auth()->user()->password !== null)
                            <x-button wire:click="$toggle('activateTwoFactorModal')" color="green">
                                {{ __('authmodule::account.overview.actions.buttons.activate_two_factor') }}
                            </x-button>
                        @endif

                        @if(setting('authmodule.enable.delete_account'))
                            <x-button color="red" wire:click="deleteAccount">
                                {{ __('authmodule::account.overview.actions.buttons.delete_account') }}
                            </x-button>
                        @endif

                        <x-view-integration name="authmodule_profile_actions"/>
                    </div>
                </x-card>
            </div>

            <div class="col-span-2 space-y-4">
                <x-card>
                    <x-slot:header>
                        <span
                            class="font-bold text-xl">{{ __('authmodule::account.overview.profile.title') }}</span>
                    </x-slot:header>

                    <form wire:submit="updateProfile">
                        @csrf
                        <div class="grid md:grid-cols-2 grid-cols-1 gap-4 mb-3">
                            <x-input label="{{ __('authmodule::messages.first_name') }} *"
                                     wire:model="firstName"/>
                            <x-input label="{{ __('authmodule::messages.last_name') }} *"
                                     wire:model="lastName"/>

                            <x-input label="{{ __('authmodule::messages.username') }} *"
                                     wire:model="username"/>
                            <x-input label="{{ __('authmodule::messages.email') }} *"
                                     wire:model="email"/>
                        </div>

                        <x-view-integration name="authmodule_profile_update"/>

                        <x-divider/>

                        <x-button class="mt-3" loading="updateProfile">
                            {{ __('authmodule::account.overview.profile.buttons.update_profile') }}
                        </x-button>
                    </form>
                </x-card>

                <div class="col-span-2 space-y-4">
                    <x-card>
                        <x-slot:header>
                        <span
                            class="font-bold text-xl">{{ __('authmodule::account.overview.password.title') }}</span>
                        </x-slot:header>

                        <form wire:submit="updatePassword">
                            @csrf

                            <div class="mb-3">
                                @if(auth()->user()->password !== null)
                                    <x-password label="{{ __('authmodule::account.overview.password.current_password') }} *"
                                                wire:model="currentPassword"/>
                                @endif
                                <div class="grid md:grid-cols-2 grid-cols-1 gap-4 @if(auth()->user()->password !== null) mt-4 @endif">
                                    <x-password label="{{ __('authmodule::messages.new_password') }} *"
                                                wire:model="newPassword"/>
                                    <x-password
                                        label="{{ __('authmodule::account.overview.password.new_password_confirmation') }} *"
                                        wire:model="newPasswordConfirmation"/>
                                </div>
                            </div>

                            <x-view-integration name="authmodule_profile_password"/>

                            <x-divider/>

                            <x-button class="mt-3" loading="updatePassword">
                                {{ __('authmodule::account.overview.password.buttons.update_password') }}
                            </x-button>

                        </form>
                    </x-card>
                </div>
            </div>
        </div>
    @endif

    @if($tab === __('authmodule::account.tabs.sessions'))
        <div class="mt-4">
            <x-card>
                <x-slot:header>
                        <span
                            class="font-bold text-xl">{{ __('authmodule::account.sessions.title') }}</span>
                </x-slot:header>

                <x-view-integration name="authmodule_profile_sessions"/>

                @livewire('authmodule::components.tables.sessions-table')
            </x-card>
        </div>
    @endif
</div>