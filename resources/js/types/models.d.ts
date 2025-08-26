export interface Product {
  id: number;
  name: string;
  price: number;
  [key: string]: any;
}

export interface Client {
  id: number;
  name: string;
  [key: string]: any;
}

export interface InvoiceItem {
  id?: number;
  product_id: number | string;
  quantity: number;
  price: number;
  product?: Product;
}

export interface Invoice {
  id: number;
  number: string;
  client_id: number | string;
  client?: Client;
  items: InvoiceItem[];
  payments?: Payment[];
  notes?: string;
  status?: string;
  date?: string;
  total?: number;
  currency?: string;
  [key: string]: any;
}

export interface Payment {
  id: number;
  date: string;
  amount: number;
  method?: string;
  [key: string]: any;
}
