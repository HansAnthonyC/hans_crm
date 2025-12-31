# Flowchart PT. Smart CRM (Mermaid)

flowchart TD
    A([Login]) --> B{Role User?}
    
    B -->|Sales| C[Dashboard Sales]
    B -->|Manager| D[Dashboard Manager]
    B -->|Admin| E[Dashboard Admin]
    
    subgraph SalesArea[" "]
        C --> C1[Kelola Lead]
        C --> C2[Lihat Customer]
        C1 --> F[Add/Edit Lead]
        F --> G{Lead Qualified?}
        G -->|Ya| H[Buat Project]
        G -->|Tidak| I[Tandai Unqualified]
        H --> J[Pilih Produk]
    end
    
    subgraph ManagerArea[" "]
        D --> D1[Lihat Semua Data]
        D --> K[Lihat Semua Project]
        K --> L{Review Project}
        L -->|Setuju| M[Ubah ke Customer]
        L -->|Tolak| N[Revisi Project]
        M --> O([Customer Aktif])
    end
    
    subgraph AdminArea[" "]
        E --> E1[Kelola Pengguna]
        E --> E2[Kelola Produk]
        E --> E3[Akses Penuh]
    end
    
    J --> K
    N --> H
