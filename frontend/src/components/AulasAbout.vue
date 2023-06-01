<template>
  <div>
    <h1>About</h1>
    <RouterLink to="/home">Go to Home</RouterLink>
    {{ id }}
  </div>
</template>

<script>
export default {
  name: "AulasAbout",
  computed: {
    id() {
      return this.$route.params.id;
    },
  },
};
</script>
