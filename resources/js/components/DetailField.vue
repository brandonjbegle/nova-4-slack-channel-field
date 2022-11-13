<template>
  <PanelItem :index="index" :field="field">
    <template #value>
      <div class="flex items-center justify-start">
        <div class="mr-2" v-if="value && value.type">
          <Hash v-if="value.type === 'public'" height="15px" width="15px"></Hash>
          <Lock v-else-if="value.type === 'private'" height="15px" width="15px"></Lock>
        </div>
        <span class="font-medium">{{ value && value.display ? value.display : ''}}</span>
      </div>
    </template>
  </PanelItem>

</template>

<script>
import Hash from "./svgs/Hash";
import Lock from "./svgs/Lock";
export default {
  props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],
  components: {
    Hash,
    Lock
  },
  computed:{
    value(){
      return this.field.value
    }
  }
}
</script>
