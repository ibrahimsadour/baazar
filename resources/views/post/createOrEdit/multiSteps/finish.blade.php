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

@section('wizard')
	@includeFirst([config('larapen.core.customizedViewPath') . 'post.createOrEdit.multiSteps.inc.wizard', 'post.createOrEdit.multiSteps.inc.wizard'])
@endsection

@section('content')
	@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
	<div class="main-container">
		<div class="container">
			<div class="row">

				@if (session()->has('flash_notification'))
					<div class="col-xl-12">
						<div class="row">
							<div class="col-xl-12">
								@include('flash::message')
							</div>
						</div>
					</div>
				@endif

				<div class="col-xl-12 page-content">

					@if (session()->has('message'))
						<div class="inner-box">
							<div class="row">
								<div class="col-xl-12">
									<div class="alert alert-success pgray alert-lg mb-0" role="alert">
										<h2 class="no-padding mb20">&#10004; {{ t('congratulations') }}</h2>
										<p class="mb-0">
											{{ session()->get('message') }} <a href="{{ url('/') }}">{{ t('Homepage') }}</a>
										</p>
									</div>
								</div>
							</div>
						</div>
					@endif

				</div>
			</div>
		</div>
	</div>
@endsection
<?php
if (!session()->has('emailVerificationSent') && !session()->has('phoneVerificationSent')) {
	if (session()->has('message')) {
		session()->forget('message');
	}
}
?>
