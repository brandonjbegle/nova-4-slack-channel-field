<template>
  <DefaultField
      :field="field"
      :errors="errors"
      :show-help-text="showHelpText"
      :full-width-content="fullWidthContent"
  >
    <template #field>
      <div class="w-full flex">
        <SearchInput :clearable="true" @input="searchChannels" :data="channels"
                     @selected="selectNewChannel"
                     @clear="clearSelectedChannel"
                     :value="selectedChannel" track-by="value"
                     class="pl-2 w-full">
          <div v-if="selectedChannel" class="flex items-center">

            <div class="mr-2">
              <Hash v-if="selectedChannel.type === 'public'" height="15px" width="15px"></Hash>
              <Lock v-else height="15px" width="15px"></Lock>
            </div>
            <span class="font-medium">{{ selectedChannel.display }}</span>
          </div>
          <template #option="{ selected, option }">
            <div class="flex items-center justify-start">
              <div class="mr-2">
                <Hash v-if="option.type === 'public'" height="15px" width="15px"></Hash>
                <Lock v-else height="15px" width="15px"></Lock>
              </div>
              <span class="font-medium">{{ option.display }}</span>
            </div>
          </template>
        </SearchInput>
        <button type="button" class="rounded bg-primary-500 ml-2 text-white px-3" @click="refreshChannels">
          <span class="flex justify-center items-center" v-if="refreshingChannels">
            <span class="spin-load ease-linear rounded-full border-2 border-t-2 border-white"></span>
          </span>
          <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" height="12" width="12" fill="white">
            <!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
            <path
                d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160H336c-17.7 0-32 14.3-32 32s14.3 32 32 32H463.5c0 0 0 0 0 0h.4c17.7 0 32-14.3 32-32V64c0-17.7-14.3-32-32-32s-32 14.3-32 32v51.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1V448c0 17.7 14.3 32 32 32s32-14.3 32-32V396.9l17.6 17.5 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352H176c17.7 0 32-14.3 32-32s-14.3-32-32-32H48.4c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"/>
          </svg>
        </button>
      </div>
    </template>
  </DefaultField>
</template>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova'
import Lock from "./svgs/Lock";
import Hash from "./svgs/Hash";

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],
  components: {
    Hash,
    Lock
  },
  data() {
    return {
      channels: '',
      selectedChannel: '',
      refreshingChannels: false,
    }
  },

  mounted() {
    this.searchChannels();
  },

  methods: {
    selectNewChannel(event) {
      this.value = event;
      this.selectedChannel = event;
    },
    clearSelectedChannel() {
      this.selectedChannel = null;
      this.value = null;
    },
    searchChannels(term = null) {
      let data = {}
      if (term)
        data.term = term;

      if (this.field.types)
        data.types = this.field.types;

      let params = new URLSearchParams(data).toString()

      Nova.request().get(`/nova-vendor/slack-channel-field/list?${params}`)
          .then(res => {
            this.channels = res.data;
          })
          .catch(err => {
            Nova.error(err, {type: 'error'})
          })
    },
    refreshChannels() {
      this.refreshingChannels = true;
      let data = {}

      if (this.field.types)
        data.types = this.field.types;

      let params = new URLSearchParams(data).toString()

      Nova.request().get(`/nova-vendor/slack-channel-field/refresh?${params}`)
          .then(res => {
            this.searchChannels();
            this.refreshingChannels = false;
          })
          .catch(err => {
            Nova.error(err, {type: 'error'})
            this.refreshingChannels = false;
          })
    },
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || ''
      this.selectedChannel = this.field.value || null;
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, JSON.stringify(this.value) || '')
    },
  },
}
</script>
