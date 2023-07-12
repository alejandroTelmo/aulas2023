<template>
  <div>
    <h1>Home</h1>
    <RouterLink to="/about/123">Go to About</RouterLink>
  </div>
</template>

<script>
import { RouterLink } from "vue-router";

export default {
  name: "AulasHome",
  components: { RouterLink },
};
</script>
