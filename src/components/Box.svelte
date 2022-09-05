<script>
  export let pulse;
  export let insurance_image;
</script>

<article>
  <div
    class="card card-background masonry-brick border-dark"
    style="clear:both;margin-top:3px;border-radius:0.6rem;--image: url({insurance_image});"
  >
    {#if pulse == "Under Processing"}
      <div
        style="width:100%;height:0.5rem;position:absolute;bottom:0;left:0;background-color:#e44040;border-radius:0 0 0.6rem 0.6rem;"
        class="pulse2"
      />
    {/if}
    <div class="card-body" style="padding-top:0.25rem;opacity:1;">
      <slot name="body" />
    </div>
  </div>
</article>

<style>

  .card-background {
    background: linear-gradient(
        rgba(255, 255, 255, 0.8),
        rgba(255, 255, 255, 0.8)
      ),
      var(--image);
    background-size: contain;
    background-position: center;
    background-repeat: no-repeat;
  }

  :root {
    --hue: 220;
    --sat: 100;
    --light: 50;
    --threshold: 65;
  }

  .pulse2 {
    animation: pulse2 2s ease-out infinite;
  }

  @keyframes pulse2 {
    50% {
      box-shadow: 0 0 0 0.07em rgba(255, 0, 0, 0.397);
      border-radius: 0 0 0.6em 0.6em;
    }
  }
  .masonry-brick {
    height: 210px;
    width: 290px;
  }
</style>
