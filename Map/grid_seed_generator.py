#%%

import pandas as pd


sq = pd.read_csv("data/grid_squares.csv", header = None)
for i, row in sq.iterrows():
    print(f"$sq = new GridSquare;\n$sq->alat = \"{row[0]}\";\n$sq->along = \"{row[1]}\";\n$sq->blat = \"{row[2]}\";\n$sq->blong = \"{row[3]}\";\n$sq->clat = \"{row[4]}\";\n$sq->clong = \"{row[5]}\";\n$sq->dlat = \"{row[6]}\";\n$sq->dlong = \"{row[7]}\";\n$sq->danger = {row[8]};\n$sq->population = {row[9]};\n$sq->save();\n")

