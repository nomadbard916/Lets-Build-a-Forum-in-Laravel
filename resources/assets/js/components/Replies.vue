<template>
  <div>
    <div v-for="reply in items" @deleted="remove(index)">
      <reply :data="reply"></reply>
    </div>
  </div>
</template>

<script>
  import Reply from './Reply.vue'

  export default {
    props: ['data'],

    components: {
      Reply
    },

    data() {
      return {
        items: this.data
      }
    },

    methods: {
      add(reply) {
        this.items.push(reply);
        this.$emit('added');
      },
      remove(index) {
        this.items.splice(index, 1);
        this.$emit('removed');
        flash('Reply was deleted!');
      }
    }
  }
</script>

<style>

</style>