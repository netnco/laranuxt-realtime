<template>
  <div>
    <h1 class="titre-general">Interface de passage de commande</h1>
    <div class="column-third">
      <h2>Passage de commande</h2>
      <div>
        <label for="exampleInput">Entrez un numéro de commande :</label>
        <input
          id="exampleInput"
          v-model="commande.numero"
          type="email"
          placeholder="N°"
          required
        >
        <button type="button" class="btn btn-primary" @click="passerCommande">Passer commande !</button>
        <p><em>({{ returned }})</em></p>
      </div>
    </div>
    <div class="column-third">
      <h2>Commandes passées</h2>
      <table v-if="commandes_passees.length > 0" class="table table-stripped small m-t-md">
        <thead>
          <tr>
            <th>Numéro</th>
            <th>Envoyée à</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="commande_passee in commandes_passees">
            <td>{{ commande_passee.numero }}</td>
            <td>{{ commande_passee.heure_creation }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="column-third">
      <h2>Commandes reçues <em>Ecoute du Channel "Livreur"..</em></h2>
      <table v-if="commandes_recues.length > 0" class="table table-stripped small m-t-md">
        <thead>
          <tr>
            <th>Numéro :</th>
            <th>Enregistrée à :</th>
            <th>Reçue à :</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="commande_recue in commandes_recues">
            <td>{{ commande_recue.numero }}</td>
            <td>{{ commande_recue.heure_reception }}</td>
            <td>{{ commande_recue.heure_reception_reelle }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
    export default {
        data() {
            return {
                commande: {
                    numero: null
                },
                commandes_passees: [],
                commandes_recues: [],
                returned: {}
            };
        },
        mounted() {
            window.Echo.channel('Livreur')
              .listen(".App\\Events\\Message", (data) => {
                  let commande_recue = JSON.parse(data.message);
                  commande_recue.heure_reception_reelle = this.quelleHeureEstTil();
                  this.commandes_recues.push(commande_recue);
              });
        },
        methods: {
            passerCommande() {
                if(this.commande) {
                    this.commande.heure_creation = this.quelleHeureEstTil();
                    this.commandes_passees.push(Object.assign({}, this.commande));
                    this.envoyerRequete(this.commande);
                }
            },
            async envoyerRequete() {
                this.$axios
                  .post('/api/commande', {
                      numero: this.commande.numero
                  },
                  {
                      headers: {
                          Authorization: `Bearer XXX`,
                          'Access-Control-Allow-Origin': '*',
                          'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS'
                      }
                  }).catch((error) => {
                    this.returned = error;
                  }).then((response) => {
                    /* eslint-disable-next-line no-console */
                    this.returned = response;
                  });
            },
            quelleHeureEstTil() {
              let date = new Date();
              const hours = date.getHours() > 9 ? date.getHours() : '0' + date.getHours();
              const minutes = date.getMinutes() > 9 ? date.getMinutes() : '0' + date.getMinutes();
              const seconds = date.getSeconds() > 9 ? date.getSeconds() : '0' + date.getSeconds();
              return hours + ':' + minutes + ':' + seconds;
            }
        }
    };
</script>

<style>
  .titre-general {
    width: 50%;
    margin: 0 auto 50px auto;
    padding-bottom: 20px;
    border: 1px solid #000;
    border-top: none;
    font-size: 40px;
    text-align: center;
  }
  .column-third {
    display: inline-table;
    width: 33%;
    padding: 30px;
    text-align: center;
  }
  table {
    width: 100%;
  }
  h2 {
    margin-bottom: 20px;
  }
  h2 em {
    font-size: 12px;
    font-weight: normal;
  }
</style>
