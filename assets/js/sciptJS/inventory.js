import React from "react";
import ReactDOM from "react-dom";
import RevenueChart from "./components/RevenueChart";


ReactDOM.render(<RevenueChart />, document.getElementById("revenue-chart"));

import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts';

const data = [
    { name: 'Q1', revenue: 30, color: '#FF0000' },
    { name: 'Q2', revenue: 50, color: '#FF8000' },
    { name: 'Q3', revenue: 70, color: '#00FF00' }
  ];
  
  const RevenueChart = () => {
    return (
      <ResponsiveContainer width="100%" height={300}>
        <BarChart data={data}>
          <CartesianGrid strokeDasharray="3 3" />
          <XAxis dataKey="name" />
          <YAxis />
          <Tooltip />
          <Bar dataKey="revenue" fill="#8884d8">
            {data.map((entry, index) => (
              <Cell key={`cell-${index}`} fill={entry.color} />
            ))}
          </Bar>
        </BarChart>
      </ResponsiveContainer>
    );
  };
  
  export default RevenueChart;