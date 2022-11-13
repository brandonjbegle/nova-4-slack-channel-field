<template>
  <div class="flex items-center justify-center">
    <div class="mr-2" v-if="fieldValue">
      <Hash v-if="fieldValue && fieldValue.type === 'public'" height="15px" width="15px"></Hash>
      <Lock v-else height="15px" width="15px"></Lock>
    </div>
    <span class="font-medium">{{ fieldValue && fieldValue.display ? fieldValue.display : '' }}</span>
  </div>
</template>

<script>
import Hash from "./svgs/Hash";
import Lock from "./svgs/Lock";
export default {
  props: ['resourceName', 'field'],
  components: {
    Lock,
    Hash
  },

  computed: {
    fieldValue() {

      return this.field.displayedAs || this.field.value;
    },
  }
}
</script>
