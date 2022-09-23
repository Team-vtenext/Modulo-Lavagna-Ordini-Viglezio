<script>
  import { onMount } from "svelte";
  import { goto, stores } from "@sapper/app";
  import jQuery from "jquery";
  import Keypad from "../components/Keypad.svelte";
  import Modal from "../components/Modal.svelte";
  import { backend } from "../config/config.backend";

  let error;
  let pin;
  let baseURL = backend.baseurl;
  let currentPin;
  let showModal = false;

  onMount(async () => {
    const res = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/boardBackend.php?action=getConfigurationFile",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
      }
    );
    let configuration = await res.json();
    if (configuration.success == "true") goto("/");
  });

  async function handleSubmit(event) {
    let data = {
      database: event.target.database.value,
      databasename: event.target.databasename.value,
      dbusername: event.target.dbusername.value,
      dbpassword: event.target.dbpassword.value,
      vteurl: event.target.vteurl.value,
      username: event.target.username.value,
      password: event.target.password.value,
      accesskey: event.target.accesskey.value,
    };

    const res = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/boardBackend.php?action=saveConfiguration",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      }
    );

    const json = await res.json();
    if (json.success == "true") {
      var bootboxalert = bootbox.alert({
        message: "Configuration Saved",
        size: "small",
      });
      setTimeout(function () {
        bootboxalert.modal("hide");
        location.reload();
      }, 2000);
    }
  }
</script>

<main role="main" class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-sm-6 col-md-4  text-center">
      <h1>Configurazione Iniziale</h1>
      <form on:submit|preventDefault={handleSubmit}>
        <p><br /></p>
        <h4>Impostazioni Database</h4>
        <div class="form-group text-left">
          <label for="database"><i class="fas fa-server" /> Database URL</label>
          <input
            type="text"
            class="form-control form-control-lg"
            id="database"
            placeholder="localhost"
          />
        </div>
        <div class="form-group text-left">
          <label for="databasename"
            ><i class="far fa-database" /> Database</label
          >
          <input
            type="text"
            class="form-control form-control-lg"
            id="databasename"
            placeholder="db_board"
          />
        </div>
        <div class="form-group text-left">
          <label for="username"><i class="far fa-user" /> DB Username</label>
          <input
            type="text"
            class="form-control form-control-lg"
            id="dbusername"
            placeholder="DB Username"
          />
        </div>
        <div class="form-group text-left">
          <label for="dbpassword"><i class="fal fa-lock" /> DB Password</label>
          <input
            type="password"
            class="form-control form-control-lg"
            id="dbpassword"
          />
        </div>
        <p><br /></p>
        <h4>Configurazione Lavagna/VTE</h4>

        <div class="form-group text-left">
          <label for="vteurl"><i class="far fa-user" /> URL VTE</label>
          <input
            type="text"
            class="form-control form-control-lg"
            id="vteurl"
            placeholder="https://vte.vtenext.ch"
          />
        </div>
        <div class="form-group text-left">
          <label for="username"><i class="far fa-user" /> Username VTE</label>
          <input
            type="text"
            class="form-control form-control-lg"
            id="username"
            placeholder="Username"
          />
        </div>
        <div class="form-group text-left">
          <label for="password"><i class="fal fa-lock" /> Password VTE</label>
          <input
            type="password"
            class="form-control form-control-lg"
            id="password"
          />
        </div>
        <div class="form-group text-left">
          <label for="accesskey"><i class="far fa-key" /> Access Key</label>
          <input
            type="text"
            class="form-control form-control-lg"
            id="accesskey"
            placeholder="123456789"
          />
        </div>
        <br /><br />
        <button type="submit" class="btn btn-success btn-lg btn-block"
          ><i class="fal fa-save" /> Salva</button
        >
        <br />
      </form>
    </div>
  </div>

  {#if showModal != false}
    <Modal on:close={() => (showModal = false)}>
      <Keypad bind:value={pin} on:submit={() => (showModal = false)} />
    </Modal>
  {/if}
</main>
