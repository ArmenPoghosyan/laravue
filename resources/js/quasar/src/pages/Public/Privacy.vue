<template>
	<ap-card flat>
		<ap-card-section class="text-center">
			<span class="text-h6 text-weight-bold">{{ $t('pages.cms.privacy.title') }}</span>
		</ap-card-section>

		<ap-card-section v-html="$t('pages.cms.privacy.content')"></ap-card-section>
	</ap-card>
</template>
