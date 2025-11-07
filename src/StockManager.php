<?php 
namespace App;
class StockManager {
    public function ajouterProduit(Produit $produit){
        $produit->save();
    }

    public function getProduits(){
        return Produit::all();
    }

    public function getProduit(int $id){
        return Produit::find($id);
    }

    public function supprimerProduit (int $id){
        Produit::delete($id);
    }

    public function totalStock(){
        $total = 0;
        foreach(Produit::all() as $p){
            $total += $p->getPrix() * $p->getQuantite();
        }
        return $total;
    }


}