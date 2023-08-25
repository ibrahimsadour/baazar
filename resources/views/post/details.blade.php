{{--
 * bazaralkhaleej - Bazaralkhaleej Ads Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://bazaralkhaleej.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
--}}
@extends('layouts.master')

@section('content')
	{!! csrf_field() !!}
	<input type="hidden" id="postId" name="post_id" value="{{ data_get($post, 'id') }}">
	
	@if (session()->has('flash_notification'))
		@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
		<?php $paddingTopExists = true; ?>
		<div class="container">
			<div class="row">
				<div class="col-12">
					@include('flash::message')
				</div>
			</div>
		</div>
		<?php session()->forget('flash_notification.message'); ?>
	@endif
	
	{{-- Archived listings message --}}
	@if (!empty(data_get($post, 'archived_at')))
		@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
		<?php $paddingTopExists = true; ?>
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="alert alert-warning" role="alert">
						{!! t('This listing has been archived') !!}
					</div>
				</div>
			</div>
		</div>
	@endif
	
	<div class="main-container">
		
		<?php if (isset($topAdvertising) && !empty($topAdvertising)): ?>
			@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.advertising.top', 'layouts.inc.advertising.top'], ['paddingTopExists' => $paddingTopExists ?? false])
		<?php
			$paddingTopExists = false;
		endif;
		?>
		
		<div class="container {{ (isset($topAdvertising) && !empty($topAdvertising)) ? 'mt-3' : 'mt-2' }}">
			<div class="row">
				<div class="col-md-12">
					
					<nav aria-label="breadcrumb" role="navigation" class="float-start">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home"></i></a></li>
							<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('country.name') }}</a></li>
							@if (isset($catBreadcrumb) && is_array($catBreadcrumb) && count($catBreadcrumb) > 0)
								@foreach($catBreadcrumb as $key => $value)
									<li class="breadcrumb-item">
										<a href="{{ $value->get('url') }}">
											{!! $value->get('name') !!}
										</a>
									</li>
								@endforeach
							@endif
							<li class="breadcrumb-item active" aria-current="page">{{ str(data_get($post, 'title'))->limit(70) }}</li>
						</ol>
					</nav>
					
					<div class="float-end backtolist">
						<a href="{{ rawurldecode(url()->previous()) }}"><i class="fa fa-angle-double-left"></i> {{ t('back_to_results') }}</a>
					</div>
				
				</div>
			</div>
		</div>
		
		<div class="container">
			<div class="row">
				<div class="col-lg-9 page-content col-thin-right">
					<?php
					$innerBoxStyle = (!auth()->check() && plugin_exists('reviews')) ? 'overflow: visible;' : '';
					?>
					<div class="inner inner-box items-details-wrapper pb-0" style="{{ $innerBoxStyle }}">
						<h1 class="h4 fw-bold enable-long-words">
							<strong>
								<a href="{{ \App\Helpers\UrlGen::post($post) }}" title="{{ data_get($post, 'title') }}">
									{{ data_get($post, 'title') }}
                                </a>
                            </strong>
							@if (config('settings.single.show_listing_types'))
								@if (!empty(data_get($post, 'postType')))
									<small class="label label-default adlistingtype">{{ data_get($post, 'postType.name') }}</small>
								@endif
							@endif
							@if (data_get($post, 'featured') == 1 && !empty(data_get($post, 'latestPayment.package')))
								<i class="fas fa-check-circle"
								   style="color: {{ data_get($post, 'latestPayment.package.ribbon') }};"
								   data-bs-placement="bottom"
								   data-bs-toggle="tooltip"
								   title="{{ data_get($post, 'latestPayment.package.short_name') }}"
								></i>
                            @endif
						</h1>
						<span class="info-row">
							@if (!config('settings.single.hide_dates'))
							<span class="date"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>
								<i class="far fa-clock"></i> {!! data_get($post, 'created_at_formatted') !!}
							</span>&nbsp;
							@endif
							{{-- <span class="category"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>
								<i class="bi bi-folder"></i> {{ data_get($post, 'category.parent.name', data_get($post, 'category.name')) }}
							</span>&nbsp;
							<span class="item-location"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>
								<i class="bi bi-geo-alt"></i> {{ data_get($post, 'city.name') }}
							</span>&nbsp; --}}
							<span class="category"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>
								<i class="bi bi-eye"></i> {{
									\App\Helpers\Number::short(data_get($post, 'visits'))
									. ' '
									. trans_choice('global.count_views', getPlural(data_get($post, 'visits')), [], config('app.locale'))
									}}
							</span>
							{{-- <span class="category float-md-end"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>
								{{ t('reference') }}: {{ hashId(data_get($post, 'id'), false, false) }}
							</span> --}}
						</span>
						
						@include('post.inc.pictures-slider')
						
						@if (config('plugins.reviews.installed'))
							@if (view()->exists('reviews::ratings-single'))
								@include('reviews::ratings-single')
							@endif
						@endif
						

						<div class="items-details">
							<ul class="nav nav-tabs" id="itemsDetailsTabs" role="tablist">
								<li class="nav-item" role="presentation">
									<button class="nav-link active"
									   id="item-details-tab"
									   data-bs-toggle="tab"
									   data-bs-target="#item-details"
									   role="tab"
									   aria-controls="item-details"
									   aria-selected="true"
									>
										<h4>{{ t('listing_details') }}</h4>
									</button>
								</li>
								@if (config('plugins.reviews.installed'))
									<li class="nav-item" role="presentation">
										<button class="nav-link"
										   id="item-{{ config('plugins.reviews.name') }}-tab"
										   data-bs-toggle="tab"
										   data-bs-target="#item-{{ config('plugins.reviews.name') }}"
										   role="tab"
										   aria-controls="item-{{ config('plugins.reviews.name') }}"
										   aria-selected="false"
										>
											<h4>
												{{ trans('reviews::messages.Reviews') }}
												@if (isset($rvPost) && !empty($rvPost))
													({{ $rvPost->rating_count }})
												@endif
											</h4>
										</button>
									</li>
								@endif
							</ul>
							
							{{-- Tab panes --}}
							<div class="tab-content p-3 mb-3" id="itemsDetailsTabsContent">
								<div class="tab-pane show active" id="item-details" role="tabpanel" aria-labelledby="item-details-tab">
									<div class="row pb-3">
										<div class="items-details-info col-md-12 col-sm-12 col-12 enable-long-words from-wysiwyg">
											
											<div class="row">
												{{-- Location --}}
												<div class="col-md-6 col-sm-6 col-6">
													<h4 class="fw-normal p-0">
														<span class="fw-bold"><i class="bi bi-geo-alt"></i> {{ t('location') }}: </span>
														<span>
															<a href="{!! \App\Helpers\UrlGen::city(data_get($post, 'city')) !!}">
																{{ data_get($post, 'city.name') }}
															</a>
														</span>
													</h4>
												</div>
		
												{{-- Price / Salary --}}
												<div class="col-md-6 col-sm-6 col-6 text-end">
													<h4 class="fw-normal p-0">
														<span class="fw-bold">
															{{ getPriceLabel(data_get($post, 'category.type')) }}
														</span>
														<span>
															{!! getPriceInfo(data_get($post, 'price'), data_get($post, 'category.type')) !!}
															@if (!in_array(data_get($post, 'category.type'), ['not-salable']))
																@if (data_get($post, 'negotiable') == 1)
																	<small class="label bg-success"> {{ t('negotiable') }}</small>
																@endif
															@endif
														</span>
													</h4>
												</div>
											</div>
											<hr class="border-0 bg-secondary">
											
											{{-- Description --}}
											<div class="row">
												<div class="col-12 detail-line-content">
													{!! transformDescription(data_get($post, 'description')) !!}
												</div>
											</div>
											
											{{-- Custom Fields --}}
											@includeFirst([config('larapen.core.customizedViewPath') . 'post.inc.fields-values', 'post.inc.fields-values'])
										
											{{-- Tags --}}
											@if (!empty(data_get($post, 'tags')))
												<div class="row mt-3">
													<div class="col-12">
														<h4 class="p-0 my-3"><i class="bi bi-tags"></i> {{ t('Tags') }}:</h4>
														@foreach(data_get($post, 'tags') as $iTag)
															<span class="d--block border border-inverse bg-light rounded-1 py-1 px-2 my-1 me-1">
																<a href="{{ \App\Helpers\UrlGen::tag($iTag) }}">
																	{{ $iTag }}
																</a>
															</span>
														@endforeach
													</div>
												</div>
											@endif
											
											{{-- Actions --}}
											@if (!auth()->check() || (auth()->check() && auth()->id() != data_get($post, 'user_id')))
												<div class="row text-center h2 mt-4">
													<div class="col-4">
													@if (auth()->check())
														@if (auth()->user()->id == data_get($post, 'user_id'))
															<a href="{{ \App\Helpers\UrlGen::editPost($post) }}">
																<i class="far fa-edit"
																   data-bs-toggle="tooltip"
																   title="{{ t('Edit') }}"
																></i>
															</a>
														@else
															{!! genEmailContactBtn($post, false, true) !!}
														@endif
													@else
														{!! genEmailContactBtn($post, false, true) !!}
													@endif
													</div>
													@if (isVerifiedPost($post))
														<div class="col-4">
															<a class="make-favorite" id="{{ data_get($post, 'id') }}" href="javascript:void(0)">
																@if (auth()->check())
																	@if (!empty(data_get($post, 'savedByLoggedUser')))
																		<i class="fas fa-bookmark"
																		   data-bs-toggle="tooltip"
																		   title="{{ t('Remove favorite') }}"
																		></i>
																	@else
																		<i class="far fa-bookmark"
																		   data-bs-toggle="tooltip"
																		   title="{{ t('Save listing') }}"
																		></i>
																	@endif
																@else
																	<i class="far fa-bookmark"
																	   data-bs-toggle="tooltip"
																	   title="{{ t('Save listing') }}"
																	></i>
																@endif
															</a>
														</div>
														<div class="col-4">
															<a href="{{ \App\Helpers\UrlGen::reportPost($post) }}">
																<i class="far fa-flag"
																   data-bs-toggle="tooltip"
																   title="{{ t('Report abuse') }}"
																></i>
															</a>
														</div>
													@endif
												</div>
											@endif
										</div>
										
									</div>
								</div>
								
								@if (config('plugins.reviews.installed'))
									@if (view()->exists('reviews::comments'))
										@include('reviews::comments')
									@endif
								@endif
							</div>
									
							{{-- <div class="content-footer text-start">
								@if (auth()->check())
									@if (auth()->user()->id == data_get($post, 'user_id'))
										<a class="btn btn-default" href="{{ \App\Helpers\UrlGen::editPost($post) }}">
											<i class="far fa-edit"></i> {{ t('Edit') }}
										</a>
									@else
										{!! genPhoneNumberBtn($post) !!}
										{!! genEmailContactBtn($post) !!}
									@endif
								@else
									{!! genPhoneNumberBtn($post) !!}
									{!! genEmailContactBtn($post) !!}
								@endif
							</div> --}}
						</div>
					</div>
				</div>

				<div class="col-lg-3 page-sidebar-right">
					<aside>
						<div class="card card-user-info sidebar-card">
							@if (auth()->check() && auth()->id() == data_get($post, 'user_id'))
								<div class="card-header">{{ t('Manage Listing') }}</div>
							@else
								<div class="block-cell user">
									<div class="cell-media">
										<img src="{{ data_get($post, 'user_photo_url') }}" alt="{{ data_get($post, 'contact_name') }}">
									</div>
									<div class="cell-content">
										<h5 class="title">{{ t('Posted by') }}</h5>
										<span class="name">
											@if (isset($user) && !empty($user))
												<a href="{{ \App\Helpers\UrlGen::user($user) }}">
													{{ data_get($post, 'contact_name') }}
												</a>
											@else
												{{ data_get($post, 'contact_name') }}
											@endif
										</span>
										
										@if (config('plugins.reviews.installed'))
											@if (view()->exists('reviews::ratings-user'))
												@include('reviews::ratings-user')
											@endif
										@endif
										
									</div>
								</div>
							@endif
							
							<div class="card-content">
								<?php $evActionStyle = 'style="border-top: 0;"'; ?>
								@if (!auth()->check() || (auth()->check() && auth()->user()->getAuthIdentifier() != data_get($post, 'user_id')))
									<div class="card-body text-start">
										<div class="grid-col">
											<div class="col from">
												<i class="bi bi-geo-alt"></i>
												<span>{{ t('location') }}</span>
											</div>
											<div class="col to">
												<span>
													<a href="{!! \App\Helpers\UrlGen::city(data_get($post, 'city')) !!}">
														{{ data_get($post, 'city.name') }}
													</a>
												</span>
											</div>
										</div>
										@if (!config('settings.single.hide_dates'))
											@if (isset($user) && !empty($user) && !empty(data_get($user, 'created_at_formatted')))
											<div class="grid-col">
												<div class="col from">
													<i class="bi bi-person-check"></i>
													<span>{{ t('Joined') }}</span>
												</div>
												<div class="col to">
													<span>{!! data_get($user, 'created_at_formatted') !!}</span>
												</div>
											</div>
											@endif
										@endif
									</div>
									<?php $evActionStyle = 'style="border-top: 1px solid #ddd;"'; ?>
								@endif
								
								<div class="ev-action" {!! $evActionStyle !!}>
									@if (auth()->check())
										@if (auth()->user()->id == data_get($post, 'user_id'))
											<a href="{{ \App\Helpers\UrlGen::editPost($post) }}" class="btn btn-default btn-block">
												<i class="far fa-edit"></i> {{ t('Update the details') }}
											</a>
											@if (config('settings.single.publication_form_type') == '1')
												<a href="{{ url('posts/' . data_get($post, 'id') . '/photos') }}" class="btn btn-default btn-block">
													<i class="fas fa-camera"></i> {{ t('Update Photos') }}
												</a>
												@if (isset($countPackages) && isset($countPaymentMethods) && $countPackages > 0 && $countPaymentMethods > 0)
													<a href="{{ url('posts/' . data_get($post, 'id') . '/payment') }}" class="btn btn-success btn-block">
														<i class="far fa-check-circle"></i> {{ t('Make It Premium') }}
													</a>
												@endif
											@endif
											@if (empty(data_get($post, 'archived_at')) && isVerifiedPost($post))
												<a href="{{ url('account/posts/list/' . data_get($post, 'id') . '/offline') }}" class="btn btn-warning btn-block confirm-simple-action">
													<i class="fas fa-eye-slash"></i> {{ t('put_it_offline') }}
												</a>
											@endif
											@if (!empty(data_get($post, 'archived_at')))
												<a href="{{ url('account/posts/archived/' . data_get($post, 'id') . '/repost') }}" class="btn btn-info btn-block confirm-simple-action">
													<i class="fa fa-recycle"></i> {{ t('re_post_it') }}
												</a>
											@endif
										@else
											{!! genPhoneNumberBtn($post, true) !!}
											{!! genEmailContactBtn($post, true) !!}
										@endif
										<?php
										try {
											if (auth()->user()->can(\App\Models\Permission::getStaffPermissions())) {
												$btnUrl = admin_url('blacklists/add') . '?';
												$btnQs = (!empty(data_get($post, 'email'))) ? 'email=' . data_get($post, 'email') : '';
												$btnQs = (!empty($btnQs)) ? $btnQs . '&' : $btnQs;
												$btnQs = (!empty(data_get($post, 'phone'))) ? $btnQs . 'phone=' . data_get($post, 'phone') : $btnQs;
												$btnUrl = $btnUrl . $btnQs;
												
												if (!isDemoDomain($btnUrl)) {
													$btnText = trans('admin.ban_the_user');
													$btnHint = $btnText;
													if (!empty(data_get($post, 'email')) && !empty(data_get($post, 'phone'))) {
														$btnHint = trans('admin.ban_the_user_email_and_phone', [
															'email' => data_get($post, 'email'),
															'phone' => data_get($post, 'phone'),
														]);
													} else {
														if (!empty(data_get($post, 'email'))) {
															$btnHint = trans('admin.ban_the_user_email', ['email' => data_get($post, 'email')]);
														}
														if (!empty(data_get($post, 'phone'))) {
															$btnHint = trans('admin.ban_the_user_phone', ['phone' => data_get($post, 'phone')]);
														}
													}
													$tooltip = ' data-bs-toggle="tooltip" data-bs-placement="bottom" title="' . $btnHint . '"';
													
													$btnOut = '<a href="'. $btnUrl .'" class="btn btn-outline-danger btn-block confirm-simple-action"'. $tooltip .'>';
													$btnOut .= $btnText;
													$btnOut .= '</a>';
													
													echo $btnOut;
												}
											}
										} catch (\Throwable $e) {}
										?>
									@else
										{!! genPhoneNumberBtn($post, true) !!}
										{!! genEmailContactBtn($post, true) !!}
									@endif
								</div>
							</div>
						</div>
						
						@if (config('settings.single.show_listing_on_googlemap'))
							<?php
							$mapHeight = 250;
							$mapPlace = (!empty(data_get($post, 'city')))
								? data_get($post, 'city.name') . ',' . config('country.name')
								: config('country.name');
							$mapUrl = getGoogleMapsEmbedUrl(config('services.googlemaps.key'), $mapPlace);
							?>
							<div class="card sidebar-card">
								<div class="card-header">{{ t('location_map') }}</div>
								<div class="card-content">
									<div class="card-body text-start p-0">
										<div class="posts-googlemaps">
											<iframe id="googleMaps" width="100%" height="{{ $mapHeight }}" src="{{ $mapUrl }}"></iframe>
										</div>
									</div>
								</div>
							</div>
						@endif
						
						@if (isVerifiedPost($post))
							@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.social.horizontal', 'layouts.inc.social.horizontal'])
						@endif
						
						<div class="card sidebar-card">
							<div class="card-header">{{ t('Safety Tips for Buyers') }}</div>
							<div class="card-content">
								<div class="card-body text-start">
									<ul class="list-check">
										<li> {{ t('Meet seller at a public place') }} </li>
										<li> {{ t('Check the item before you buy') }} </li>
										<li> {{ t('Pay only after collecting the item') }} </li>
									</ul>
                                    <?php $tipsLinkAttributes = getUrlPageByType('tips'); ?>
                                    @if (!str_contains($tipsLinkAttributes, 'href="#"') && !str_contains($tipsLinkAttributes, 'href=""'))
									<p>
										<a class="float-end" {!! $tipsLinkAttributes !!}>
                                            {{ t('Know more') }}
                                            <i class="fa fa-angle-double-right"></i>
                                        </a>
                                    </p>
                                    @endif
								</div>
							</div>
						</div>
					</aside>
				</div>
			</div>

		</div>
		
		@if (config('settings.single.similar_listings') == '1' || config('settings.single.similar_listings') == '2')
			<?php $widgetType = (config('settings.single.similar_listings_in_carousel') ? 'carousel' : 'normal') ?>
			@includeFirst([
					config('larapen.core.customizedViewPath') . 'search.inc.posts.widget.' . $widgetType,
					'search.inc.posts.widget.' . $widgetType
				],
				['widget' => ($widgetSimilarPosts ?? null), 'firstSection' => false]
			)
		@endif
		
		@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.advertising.bottom', 'layouts.inc.advertising.bottom'], ['firstSection' => false])
		
		@if (isVerifiedPost($post))
			@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.tools.facebook-comments', 'layouts.inc.tools.facebook-comments'], ['firstSection' => false])
		@endif
		
	</div>
@endsection
<?php
if (!session()->has('emailVerificationSent') && !session()->has('phoneVerificationSent')) {
	if (session()->has('message')) {
		session()->forget('message');
	}
}
?>

@section('modal_message')
	@if (config('settings.single.show_security_tips') == '1')
		@includeFirst([config('larapen.core.customizedViewPath') . 'post.inc.security-tips', 'post.inc.security-tips'])
	@endif
	@if (auth()->check() || config('settings.single.guests_can_contact_authors')=='1')
		@includeFirst([config('larapen.core.customizedViewPath') . 'account.messenger.modal.create', 'account.messenger.modal.create'])
	@endif
@endsection

@section('after_styles')
@endsection

@section('before_scripts')
	<script>
		var showSecurityTips = '{{ config('settings.single.show_security_tips', '0') }}';
	</script>
@endsection

@section('after_scripts')
    @if (config('services.googlemaps.key'))
		{{-- More Info: https://developers.google.com/maps/documentation/javascript/versions --}}
        <script async src="https://maps.googleapis.com/maps/api/js?v=weekly&key={{ config('services.googlemaps.key') }}"></script>
    @endif
    
	<script>
		{{-- Favorites Translation --}}
        var lang = {
            labelSavePostSave: "{!! t('Save listing') !!}",
            labelSavePostRemove: "{!! t('Remove favorite') !!}",
            loginToSavePost: "{!! t('Please log in to save the Listings') !!}",
            loginToSaveSearch: "{!! t('Please log in to save your search') !!}"
        };
		
		$(document).ready(function () {
			{{-- Tooltip --}}
			var tooltipTriggerList = [].slice.call(document.querySelectorAll('[rel="tooltip"]'));
			var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
				return new bootstrap.Tooltip(tooltipTriggerEl)
			});
			
			@if (config('settings.single.show_listing_on_googlemap'))
				{{--
				let mapUrl = '{{ addslashes($mapUrl) }}';
				let iframe = document.getElementById('googleMaps');
				iframe.setAttribute('src', mapUrl);
				--}}
			@endif
			
			{{-- Keep the current tab active with Twitter Bootstrap after a page reload --}}
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                /* save the latest tab; use cookies if you like 'em better: */
                /* localStorage.setItem('lastTab', $(this).attr('href')); */
				localStorage.setItem('lastTab', $(this).attr('data-bs-target'));
            });
			{{-- Go to the latest tab, if it exists: --}}
            let lastTab = localStorage.getItem('lastTab');
            if (lastTab) {
				{{-- let triggerEl = document.querySelector('a[href="' + lastTab + '"]'); --}}
				let triggerEl = document.querySelector('button[data-bs-target="' + lastTab + '"]');
				if (typeof triggerEl !== 'undefined' && triggerEl !== null) {
					let tabObj = new bootstrap.Tab(triggerEl);
					if (tabObj !== null) {
						tabObj.show();
					}
				}
            }
		});
	</script>
<style>
/* css castuim made from Ibrahim 25/08/2023 for call button */
.dUihZb {
    bottom: 0px;
    gap: 10px;
    z-index: 4;
    box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 20px;
}

.p-8 {
    padding: 8px;
}
.width-100 {
    width: 100%;
}
.fixed {
    position: fixed;
    backface-visibility: hidden;
}
.gap-10 {
    gap: 10px;
}

.mb-8 {
    margin-bottom: 8px;
}
.me-8 {
    margin-left: 8px;
}
.vMiddle {
    vertical-align: middle;
}
.alignItems {
    -webkit-box-align: center;
    align-items: center;
}
.justifyContent {
    -webkit-box-pack: center;
    justify-content: center;
}
input[type="submit"], input[type="button"], button, [class*="Btn"] {
    border: 0px;
}
.blueBg, .blueHoverBg:hover, .blueBtn {
    background-color: #1d8eff ;
}
input[type="submit"]:not(.noRipple), input[type="button"]:not(.noRipple), button:not(.noRipple), [class*="Btn"]:not(.noRipple), .ripple {
    position: relative;
    overflow: hidden;
    transform: translateZ(0px) perspective(1px);
}
.font-19 {
	font-size: 15px;
	line-height: 1.1875em;
}
.height-55 {
    height: 45px;
}
.flex {
    display: flex;
}

.whiteBtn, .whiteBtn-2 {
    color: #fff !important ;
    background-color: #10b981!important;
    border: 2px solid #10b981 !important;

}
.orangeBtn, .greenBtn, .grayBtn, .blueBtn, .whiteBtn-3, .whiteBtn, .whiteBtn-2, .fbBtn, .waBtn {
    color: rgb(255, 255, 255);
    border-radius: 8px;
    text-align: center;
    font-weight: bold;
    width: 100%;
}
.chat{
	width: 33%;
    background-color: #115191!important;
    border: 2px solid #115191 !important;

}
@media screen and (min-width:768px){
    .dUihZb{
        display: none;
    }
}
/* @media screen and (max-width:768px){
.ev-action{
    display: none;
    }
} */

</style>
	<div class="dUihZb whiteBg width-100 p-8 fixed">
		<div class="flex gap-10 mb-8">
			<a type="button" href='tel:{{ data_get($post, 'phone')}}'  class="flex justifyContent alignItems btn font-19 vMiddle height-55 blueBtn">
				<svg viewBox="0 0 16 16" width="19" height="19" class=" vMiddle me-8" data-name="iconPhone">
					<path fill="#ffff" d="M4.25 0.5H1.33333C0.875 0.5 0.5 0.875 0.5 1.33333C0.5 9.15833 6.84167 15.5 14.6667 15.5C15.125 15.5 15.5 15.125 15.5 14.6667V11.7583C15.5 11.3 15.125 10.925 14.6667 10.925C13.6333 10.925 12.625 10.7583 11.6917 10.45C11.6083 10.4167 11.5167 10.4083 11.4333 10.4083C11.2167 10.4083 11.0083 10.4917 10.8417 10.65L9.00833 12.4833C6.65 11.275 4.71667 9.35 3.51667 6.99167L5.35 5.15833C5.58333 4.925 5.65 4.6 5.55833 4.30833C5.25 3.375 5.08333 2.375 5.08333 1.33333C5.08333 0.875 4.70833 0.5 4.25 0.5Z"></path>
				</svg>
				<span>إتصل الان </span>
			</a>
			<a  type="button" href='https://wa.me/{{ data_get($post, 'phone')}}' class="sc-1ccec9e8-26 dtuwSc flex justifyContent alignItems whiteBtn font-19 height-55">
				<svg viewBox="0 0 26 26" class="vMiddle me-8" width="24" height="24" data-name="iconChat">
					<path d="M16.6 14C16.4 13.9 15.1 13.3 14.9 13.2C14.7 13.1 14.5 13.1 14.3 13.3C14.1 13.5 13.7 14.1 13.5 14.3C13.4 14.5 13.2 14.5 13 14.4C12.3 14.1 11.6 13.7 11 13.2C10.5 12.7 10 12.1 9.6 11.5C9.5 11.3 9.6 11.1 9.7 11C9.8 10.9 9.9 10.7 10.1 10.6C10.2 10.5 10.3 10.3 10.3 10.2C10.4 10.1 10.4 9.90001 10.3 9.80001C10.2 9.70001 9.7 8.50001 9.5 8.00001C9.4 7.30001 9.2 7.30001 9 7.30001C8.9 7.30001 8.7 7.30001 8.5 7.30001C8.3 7.30001 8 7.50001 7.9 7.60001C7.3 8.20001 7 8.90001 7 9.70001C7.1 10.6 7.4 11.5 8 12.3C9.1 13.9 10.5 15.2 12.2 16C12.7 16.2 13.1 16.4 13.6 16.5C14.1 16.7 14.6 16.7 15.2 16.6C15.9 16.5 16.5 16 16.9 15.4C17.1 15 17.1 14.6 17 14.2C17 14.2 16.8 14.1 16.6 14ZM19.1 4.90001C15.2 1.00001 8.9 1.00001 5 4.90001C1.8 8.10001 1.2 13 3.4 16.9L2 22L7.3 20.6C8.8 21.4 10.4 21.8 12 21.8C17.5 21.8 21.9 17.4 21.9 11.9C22 9.30001 20.9 6.80001 19.1 4.90001ZM16.4 18.9C15.1 19.7 13.6 20.2 12 20.2C10.5 20.2 9.1 19.8 7.8 19.1L7.5 18.9L4.4 19.7L5.2 16.7L5 16.4C2.6 12.4 3.8 7.40001 7.7 4.90001C11.6 2.40001 16.6 3.70001 19 7.50001C21.4 11.4 20.3 16.5 16.4 18.9Z" fill="#ffffff"></path>
				</svg>
				واتساب
			</a>
			<a  type="button" href='#contactUser' class=" chat sc-1ccec9e8-26 dtuwSc flex justifyContent alignItems whiteBtn font-19 height-55">
				<svg viewBox="0 0 26 26" class="vMiddle me-8" width="24" height="24" data-name="iconChat">
					<path d="M17.0008 9H7.00084C6.73563 9 6.48127 9.10536 6.29374 9.29289C6.1062 9.48043 6.00084 9.73478 6.00084 10C6.00084 10.2652 6.1062 10.5196 6.29374 10.7071C6.48127 10.8946 6.73563 11 7.00084 11H17.0008C17.2661 11 17.5204 10.8946 17.708 10.7071C17.8955 10.5196 18.0008 10.2652 18.0008 10C18.0008 9.73478 17.8955 9.48043 17.708 9.29289C17.5204 9.10536 17.2661 9 17.0008 9ZM13.0008 13H7.00084C6.73563 13 6.48127 13.1054 6.29374 13.2929C6.1062 13.4804 6.00084 13.7348 6.00084 14C6.00084 14.2652 6.1062 14.5196 6.29374 14.7071C6.48127 14.8946 6.73563 15 7.00084 15H13.0008C13.2661 15 13.5204 14.8946 13.708 14.7071C13.8955 14.5196 14.0008 14.2652 14.0008 14C14.0008 13.7348 13.8955 13.4804 13.708 13.2929C13.5204 13.1054 13.2661 13 13.0008 13ZM12.0008 2C10.6876 2 9.38726 2.25866 8.17401 2.7612C6.96075 3.26375 5.85836 4.00035 4.92977 4.92893C3.05441 6.8043 2.00084 9.34784 2.00084 12C1.9921 14.3091 2.79164 16.5485 4.26084 18.33L2.26084 20.33C2.12208 20.4706 2.02809 20.6492 1.99071 20.8432C1.95334 21.0372 1.97426 21.2379 2.05084 21.42C2.1339 21.5999 2.26855 21.7511 2.43769 21.8544C2.60683 21.9577 2.80284 22.0083 3.00084 22H12.0008C14.653 22 17.1965 20.9464 19.0719 19.0711C20.9473 17.1957 22.0008 14.6522 22.0008 12C22.0008 9.34784 20.9473 6.8043 19.0719 4.92893C17.1965 3.05357 14.653 2 12.0008 2ZM12.0008 20H5.41084L6.34084 19.07C6.52709 18.8826 6.63164 18.6292 6.63164 18.365C6.63164 18.1008 6.52709 17.8474 6.34084 17.66C5.03143 16.352 4.21602 14.6305 4.03353 12.7888C3.85105 10.947 4.31279 9.09901 5.34007 7.55952C6.36735 6.02004 7.89663 4.88436 9.66736 4.34597C11.4381 3.80759 13.3407 3.8998 15.0511 4.60691C16.7614 5.31402 18.1737 6.59227 19.0473 8.22389C19.9209 9.85551 20.2017 11.7395 19.842 13.555C19.4822 15.3705 18.5042 17.005 17.0744 18.1802C15.6446 19.3554 13.8516 19.9985 12.0008 20Z" fill="#ffffff"></path>
				</svg>
			</a>
		</div>
	</div>
@endsection