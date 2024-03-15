import pandas as pd


adjacency_matrix_df = pd.read_csv('data/node_adj.csv', header=None)

edges = []
for i, row in adjacency_matrix_df.iterrows():
    for j, value in enumerate(row):
        if value != 0 and i < j:  # Add edge if there's a connection and avoid duplicates
            edges.append((i, j))


edges_df = pd.DataFrame(edges, columns=['node1', 'node2'])


edges_df.to_csv('data/routes_list.csv', index=False)
