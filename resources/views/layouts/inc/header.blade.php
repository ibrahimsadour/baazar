<?php
// Search parameters
$queryString = (request()->getQueryString() ? ('?' . request()->getQueryString()) : '');

// Check if the Multi-Countries selection is enabled
$multiCountriesIsEnabled = false;
$multiCountriesLabel = '';
if (config('settings.geo_location.show_country_flag')) {
	if (!empty(config('country.code'))) {
		if (isset($countries) && $countries->count() > 1) {
			$multiCountriesIsEnabled = true;
			$multiCountriesLabel = 'title="' . t('Select a Country') . '"';
		}
	}
}

// Logo Label
$logoLabel = '';
if ($multiCountriesIsEnabled) {
	$logoLabel = config('settings.app.name') . ((!empty(config('country.name'))) ? ' ' . config('country.name') : '');
}
?>
<div class="header">
	<nav class="navbar fixed-top navbar-site navbar-light bg-light navbar-expand-md" role="navigation">
		<div class="container">
			
			<div class="navbar-identity p-sm-0">

				{{-- Toggle Nav (Mobile) --}}
				<button style="float: right!important;height: 35px;padding: 0px 5px;border-color: #fff;" class="navbar-toggler -toggler float-end"
						type="button"
						data-bs-toggle="collapse"
						data-bs-target="#navbarsDefault"
						aria-controls="navbarsDefault"
						aria-expanded="false"
						aria-label="Toggle navigation"
						>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="30" height="30" focusable="false">
						<title>{{ t('Menu') }}</title>
						<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" d="M4 7h22M4 15h22M4 23h22"></path>
					</svg>
				</button>
				
				{{-- Logo --}}
				<a href="{{ url('/') }}" class="navbar-brand logo logo-title" style="margin-top: -8px;margin-right: 10px;">
					<img src="{{ config('settings.app.logo_url') }}"
						 alt="{{ strtolower(config('settings.app.name')) }}" class="main-logo" data-bs-placement="bottom"
						 data-bs-toggle="tooltip"
						 title="{!! $logoLabel !!}"/>
				</a>

				{{-- add ads --}}
				@if (!auth()->check() && config('settings.single.guests_can_post_listings') != '1')
					<a class="navbar-toggler float-end btn btn-lg btn-listing" style="line-height: 30px; height: 30px;" href="#quickLogin" data-bs-toggle="modal">
						<svg width="18" height="18" viewBox="0 0 24 24" class="inline vMiddle" xmlns="http://www.w3.org/2000/svg" data-name="IconAddPost">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M8.14179 2.65377C8.5208 2.23736 9.05779 2 9.62087 2H14.4844C15.0475 2 15.5845 2.23735 15.9635 2.65377L17.3911 4.22222H20.8947C22.1105 4.22222 23.1053 5.22222 23.1053 6.44444V19.7778C23.1053 21 22.1105 22 20.8947 22H3.21053C1.99474 22 1 21 1 19.7778V6.44444C1 5.22222 1.99474 4.22222 3.21053 4.22222H6.71421L8.14179 2.65377ZM6.52632 13.1112C6.52632 16.1778 9.00211 18.6667 12.0526 18.6667C15.1032 18.6667 17.5789 16.1778 17.5789 13.1112C17.5789 10.0445 15.1032 7.55561 12.0526 7.55561C9.00211 7.55561 6.52632 10.0445 6.52632 13.1112Z" fill="#fff">
							</path>
							<path fill-rule="evenodd" clip-rule="evenodd" d="M15.3684 13.5874H12.5263V16.4445H11.5789V13.5874H8.73682V12.635H11.5789V9.77783H12.5263V12.635H15.3684V13.5874Z" fill="#fff" stroke="#fff" stroke-width="0.1"></path>
						</svg> <span style="color: #fff">{{ t('Create Listing') }}</span> 
					</a>
				@else
				<li class="nav-item dropdown no-arrow open-on-hover navbar-toggler float-end btn">
					<a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
						<i class="fas fa-user-circle"></i>
						<span>{{ auth()->user()->name }}</span>
						<span class="badge badge-pill badge-important count-threads-with-new-messages d-lg-inline-block d-md-none">0</span>
						<i class="bi bi-chevron-down"></i>
					</a>
					<ul id="userMenuDropdown" class="dropdown-menu user-menu shadow-sm">
						@if (isset($userMenu) && !empty($userMenu))
							@php
								$menuGroup = '';
								$dividerNeeded = false;
							@endphp
							@foreach($userMenu as $key => $value)
								@continue(!$value['inDropdown'])
								@php
									if ($menuGroup != $value['group']) {
										$menuGroup = $value['group'];
										if (!empty($menuGroup) && !$loop->first) {
											$dividerNeeded = true;
										}
									} else {
										$dividerNeeded = false;
									}
								@endphp
								@if ($dividerNeeded)
									<li class="dropdown-divider"></li>
								@endif
								<li class="dropdown-item{{ (isset($value['isActive']) && $value['isActive']) ? ' active' : '' }}">
									<a href="{{ $value['url'] }}">
										<i class="{{ $value['icon'] }}"></i> {{ $value['name'] }}
										@if (isset($value['countVar'], $value['countCustomClass']) && !empty($value['countVar']) && !empty($value['countCustomClass']))
											<span class="badge badge-pill badge-important{{ $value['countCustomClass'] }}">0</span>
										@endif
									</a>
								</li>
							@endforeach
						@endif
					</ul>
				</li>
				@endif

				{{-- Country Flag (Mobile) --}}
				@if ($multiCountriesIsEnabled)
					@if (!empty(config('country.icode')))
						@if (file_exists(public_path() . '/images/flags/24/' . config('country.icode') . '.png'))
							<button class="flag-menu country-flag d-md-none d-sm-block d-none btn btn-default float-end" href="#selectCountry" data-bs-toggle="modal">
								<img src="{{ url('images/flags/24/' . config('country.icode') . '.png') . getPictureVersion() }}"
									 alt="{{ config('country.name') }}"
									 style="float: left;"
								>
								<span class="caret d-none"></span>
							</button>
						@endif
					@endif
				@endif
			</div>
			
			<div class="navbar-collapse collapse" id="navbarsDefault">
				<ul class="nav navbar-nav me-md-auto navbar-left">
					{{-- Country Flag --}}
					@if (config('settings.geo_location.show_country_flag'))
						@if (!empty(config('country.icode')))
							@if (file_exists(public_path() . '/images/flags/32/' . config('country.icode') . '.png'))
								<li class="flag-menu country-flag d-md-block d-sm-none d-none nav-item"
									data-bs-toggle="tooltip"
									data-bs-placement="{{ (config('lang.direction') == 'rtl') ? 'bottom' : 'right' }}" {!! $multiCountriesLabel !!}
								>
									@if ($multiCountriesIsEnabled)
										<a class="nav-link p-0" data-bs-toggle="modal" data-bs-target="#selectCountry">
											<img class="flag-icon mt-1"
												 src="{{ url('images/flags/32/' . config('country.icode') . '.png') . getPictureVersion() }}"
												 alt="{{ config('country.name') }}"
											>
											<span class="caret d-lg-block d-md-none d-sm-none d-none float-end mt-3 mx-1"></span>
										</a>
									@else
										<a class="p-0" style="cursor: default;">
											<img class="flag-icon"
												 src="{{ url('images/flags/32/' . config('country.icode') . '.png') . getPictureVersion() }}"
												 alt="{{ config('country.name') }}"
											>
										</a>
									@endif
								</li>
							@endif
						@endif
					@endif
				</ul>
				
				<ul class="nav navbar-nav ms-auto navbar-right">
					@if (config('settings.list.display_browse_listings_link'))
						<li class="nav-item d-lg-block d-md-none d-sm-block d-block">
							@php
								$currDisplay = config('settings.list.display_mode');
								$browseListingsIconClass = 'fas fa-th-large';
								if ($currDisplay == 'make-list') {
									$browseListingsIconClass = 'fas fa-th-list';
								}
								if ($currDisplay == 'make-compact') {
									$browseListingsIconClass = 'fas fa-bars';
								}
							@endphp
							<a href="{{ \App\Helpers\UrlGen::searchWithoutQuery() }}" class="nav-link">
								<i class="{{ $browseListingsIconClass }}"></i> {{ t('Browse Listings') }}
							</a>
						</li>
					@endif
					@if (!auth()->check())
						<li class="nav-item dropdown no-arrow open-on-hover d-md-block d-sm-none d-none">
							<a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
								<i class="fas fa-user"></i>
								<span>{{ t('log_in') }}</span>
								<i class="bi bi-chevron-down"></i>
							</a>
							<ul id="authDropdownMenu" class="dropdown-menu user-menu shadow-sm">
								<li class="dropdown-item">
									@if (config('settings.security.login_open_in_modal'))
										<a href="#quickLogin" class="nav-link" data-bs-toggle="modal"><i class="fas fa-user"></i> {{ t('log_in') }}</a>
									@else
										<a href="{{ \App\Helpers\UrlGen::login() }}" class="nav-link"><i class="fas fa-user"></i> {{ t('log_in') }}</a>
									@endif
								</li>
								<li class="dropdown-item">
									<a href="{{ \App\Helpers\UrlGen::register() }}" class="nav-link"><i class="far fa-user"></i> {{ t('sign_up') }}</a>
								</li>
							</ul>
						</li>
						<li class="nav-item d-md-none d-sm-block d-block">
							@if (config('settings.security.login_open_in_modal'))
								<a href="#quickLogin" class="nav-link" data-bs-toggle="modal"><i class="fas fa-user"></i> {{ t('log_in') }}</a>
							@else
								<a href="{{ \App\Helpers\UrlGen::login() }}" class="nav-link"><i class="fas fa-user"></i> {{ t('log_in') }}</a>
							@endif
						</li>
						<li class="nav-item d-md-none d-sm-block d-block">
							<a href="{{ \App\Helpers\UrlGen::register() }}" class="nav-link"><i class="far fa-user"></i> {{ t('sign_up') }}</a>
						</li>
					@else
						<li class="nav-item dropdown no-arrow open-on-hover">
							<a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
								<i class="fas fa-user-circle"></i>
								<span>{{ auth()->user()->name }}</span>
								<span class="badge badge-pill badge-important count-threads-with-new-messages d-lg-inline-block d-md-none">0</span>
								<i class="bi bi-chevron-down"></i>
							</a>
							<ul id="userMenuDropdown" class="dropdown-menu user-menu shadow-sm">
								@if (isset($userMenu) && !empty($userMenu))
									@php
										$menuGroup = '';
										$dividerNeeded = false;
									@endphp
									@foreach($userMenu as $key => $value)
										@continue(!$value['inDropdown'])
										@php
											if ($menuGroup != $value['group']) {
												$menuGroup = $value['group'];
												if (!empty($menuGroup) && !$loop->first) {
													$dividerNeeded = true;
												}
											} else {
												$dividerNeeded = false;
											}
										@endphp
										@if ($dividerNeeded)
											<li class="dropdown-divider"></li>
										@endif
										<li class="dropdown-item{{ (isset($value['isActive']) && $value['isActive']) ? ' active' : '' }}">
											<a href="{{ $value['url'] }}">
												<i class="{{ $value['icon'] }}"></i> {{ $value['name'] }}
												@if (isset($value['countVar'], $value['countCustomClass']) && !empty($value['countVar']) && !empty($value['countCustomClass']))
													<span class="badge badge-pill badge-important{{ $value['countCustomClass'] }}">0</span>
												@endif
											</a>
										</li>
									@endforeach
								@endif
							</ul>
						</li>
					@endif
					
					@if (config('plugins.currencyexchange.installed'))
						@include('currencyexchange::select-currency')
					@endif
					
					@if (config('settings.single.pricing_page_enabled') == '2')
						<li class="nav-item pricing">
							<a href="{{ \App\Helpers\UrlGen::pricing() }}" class="nav-link">
								<i class="fas fa-tags"></i> {{ t('pricing_label') }}
							</a>
						</li>
					@endif
					
					<?php
					$addListingUrl = \App\Helpers\UrlGen::addPost();
					$addListingAttr = '';
					if (!auth()->check()) {
						if (config('settings.single.guests_can_post_listings') != '1') {
							$addListingUrl = '#quickLogin';
							$addListingAttr = ' data-bs-toggle="modal"';
						}
					}
					if (config('settings.single.pricing_page_enabled') == '1') {
						$addListingUrl = \App\Helpers\UrlGen::pricing();
						$addListingAttr = '';
					}
					?>
					<li class="nav-item postadd">
						<a class="btn btn-block btn-border btn-listing" style="line-height: 0px;" href="{{ $addListingUrl }}"{!! $addListingAttr !!}>
							<i class="far fa-edit"></i> {{ t('Create Listing') }}
						</a>
					</li>
					
					@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.menu.select-language', 'layouts.inc.menu.select-language'])
				
				</ul>
			</div>
		
		
		</div>
	</nav>
</div>
