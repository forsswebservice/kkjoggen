<template>
  <div style="width: 100px;">
      <div v-if="fieldValue" :class="bgClass" class="w-full text-white text-center rounded-full font-bold px-2">
          {{ fieldValue }}
      </div>
      <div v-else class="w-full pl-10">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
          </svg>
      </div>
  </div>
</template>

<script>
export default {
  props: ['resourceName', 'field'],

  computed: {
    bgClass() {
        switch(this.fieldValue) {
            case 'Betald':
                return 'bg-green-500';
            case 'Ej betald':
                return 'bg-red-500';
            case 'Avbruten':
                return 'bg-yellow-500';
            default:
                return '';
        }
    },

    fieldValue() {
      return this.field.displayedAs || this.field.value
    },
  },

  mounted() {
    this.$el.closest('tr').setAttribute('style', this.field.style);
  }
}
</script>
