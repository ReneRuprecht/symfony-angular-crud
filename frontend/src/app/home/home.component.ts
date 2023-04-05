import { Component, OnInit } from '@angular/core';
import { HomeService } from './service/home.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss'],
})
export class HomeComponent implements OnInit {
  ngOnInit(): void {
    this.homeService.getHomeResponseFromBackend().subscribe({
      next: (data: any) => (this.title = data.message || 'title not found'),
      error: (error: any) => console.log(error),
    });
  }
  constructor(private homeService: HomeService) {}
  title = '';
}
